<?php

namespace Tests\Feature;

use App\Models\Carrier;
use App\Models\Location;
use App\Models\Shipment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DelayCalculationTest extends TestCase
{
    use RefreshDatabase;

    private Carrier $carrier;
    private Location $origin;
    private Location $destination;
    private User $operator;

    protected function setUp(): void
    {
        parent::setUp();

        $this->operator = User::factory()->create(['role' => 'operator']);

        $this->carrier = Carrier::create([
            'name'          => 'Maersk Line',
            'code'          => 'MAEU',
            'contact_email' => 'ops@maersk.test',
            'is_active'     => true,
        ]);

        $this->origin = Location::create([
            'name'      => 'Tanjung Priok',
            'code'      => 'IDJKT',
            'city'      => 'Jakarta',
            'country'   => 'Indonesia',
            'latitude'  => -6.10,
            'longitude' => 106.88,
        ]);

        $this->destination = Location::create([
            'name'      => 'Port of Singapore',
            'code'      => 'SGSIN',
            'city'      => 'Singapore',
            'country'   => 'Singapore',
            'latitude'  => 1.29,
            'longitude' => 103.85,
        ]);
    }

    private function createShipmentWithDates(Carbon $expectedArrival, Carbon $estimatedArrival): Shipment
    {
        $customer = User::factory()->create(['role' => 'customer']);

        return Shipment::create([
            'shipment_number'    => 'CT-DELAY-' . uniqid(),
            'user_id'            => $customer->id,
            'carrier_id'         => $this->carrier->id,
            'origin_id'          => $this->origin->id,
            'destination_id'     => $this->destination->id,
            'cargo_type'         => 'Textiles 20ft',
            'total_weight'       => 12000,
            'freight_cost'       => 18000000,
            'status'             => 'IN_TRANSIT',
            'payment_status'     => 'PAID',
            'expected_departure' => now(),
            'expected_arrival'   => $expectedArrival,
            'estimated_arrival'  => $estimatedArrival,
        ]);
    }

    /**
     * Test Slide 9 & 16: Deterministic delay calculation engine categories.
     */
    public function test_deterministic_delay_engine_classifies_correctly(): void
    {
        $baseExpected = Carbon::parse('2026-11-20 12:00:00');

        // Case 1: On-time (diff = 0)
        $onTime = $this->createShipmentWithDates($baseExpected, $baseExpected->copy());
        $this->assertEquals(0, $onTime->delay_hours);
        $this->assertEquals('ON_TIME', $onTime->delay_status);

        // Case 2: Minor delay (diff = 3 hours, range 1-4h)
        $minor = $this->createShipmentWithDates($baseExpected, $baseExpected->copy()->addHours(3));
        $this->assertEquals(3, $minor->delay_hours);
        $this->assertEquals('MINOR_DELAY', $minor->delay_status);

        // Case 3: Medium delay (diff = 8 hours, range 5-12h)
        $medium = $this->createShipmentWithDates($baseExpected, $baseExpected->copy()->addHours(8));
        $this->assertEquals(8, $medium->delay_hours);
        $this->assertEquals('MEDIUM_DELAY', $medium->delay_status);

        // Case 4: Critical delay (diff = 24 hours, range >12h)
        $critical = $this->createShipmentWithDates($baseExpected, $baseExpected->copy()->addHours(24));
        $this->assertEquals(24, $critical->delay_hours);
        $this->assertEquals('CRITICAL_DELAY', $critical->delay_status);
    }

    /**
     * Test Slide 8 & 9: Logging operational exception auto-adjusts ETA and delay metrics.
     */
    public function test_logging_operational_exception_auto_adjusts_eta(): void
    {
        $baseExpected = Carbon::parse('2026-11-20 12:00:00');
        $shipment = $this->createShipmentWithDates($baseExpected, $baseExpected->copy());

        $this->assertEquals(0, $shipment->delay_hours);

        // Operator logs a Weather Delay of 6 hours
        $response = $this->actingAs($this->operator, 'sanctum')->postJson("/api/shipments/{$shipment->id}/exceptions", [
            'exception_type'     => 'WEATHER_DELAY',
            'description'        => 'Severe tropical storm in Karimata Strait. Vessel slowed to 6 knots.',
            'delay_impact_hours' => 6,
            'location_id'        => $this->origin->id,
        ]);

        $response->assertStatus(201);
        $response->assertJson([
            'success' => true,
            'data'    => [
                'new_delay_hours'  => 6,
                'new_delay_status' => 'MEDIUM_DELAY',
            ],
        ]);

        // Verify database state
        $fresh = $shipment->fresh();
        $this->assertEquals(6, $fresh->delay_hours);
        $this->assertEquals('MEDIUM_DELAY', $fresh->delay_status);
        $this->assertEquals('2026-11-20 18:00:00', $fresh->estimated_arrival->format('Y-m-d H:i:s'));
    }
}
