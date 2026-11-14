<?php

namespace Tests\Feature;

use App\Models\Carrier;
use App\Models\Location;
use App\Models\Shipment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentSimulationTest extends TestCase
{
    use RefreshDatabase;

    private Carrier $carrier;
    private Location $origin;
    private Location $destination;

    protected function setUp(): void
    {
        parent::setUp();

        $this->carrier = Carrier::create([
            'name'          => 'Samudera Indonesia',
            'code'          => 'SMDR',
            'contact_email' => 'ops@samudera.test',
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

    /**
     * Test Slide 7: Payment simulation transitions shipment from CREATED/UNPAID to BOOKED/PAID.
     */
    public function test_payment_simulation_transitions_shipment_to_booked_and_paid(): void
    {
        $customer = User::factory()->create(['role' => 'customer']);

        $shipment = Shipment::create([
            'shipment_number'    => 'CT-PAY-001',
            'user_id'            => $customer->id,
            'carrier_id'         => $this->carrier->id,
            'origin_id'          => $this->origin->id,
            'destination_id'     => $this->destination->id,
            'cargo_type'         => 'Electronics 20ft',
            'total_weight'       => 15000,
            'freight_cost'       => 20000000,
            'status'             => 'CREATED',
            'payment_status'     => 'UNPAID',
            'expected_departure' => now(),
            'expected_arrival'   => now()->addDays(3),
            'estimated_arrival'  => now()->addDays(3),
        ]);

        $response = $this->actingAs($customer)->post(route('shipments.pay', $shipment));

        $response->assertRedirect(route('shipments.show', $shipment));
        $response->assertSessionHas('success');

        // Verify status in DB changed to BOOKED and PAID
        $fresh = $shipment->fresh();
        $this->assertEquals('BOOKED', $fresh->status);
        $this->assertEquals('PAID', $fresh->payment_status);

        // Verify tracking event PAYMENT_SETTLED was created
        $this->assertDatabaseHas('tracking_events', [
            'shipment_id' => $shipment->id,
            'event_type'  => 'PAYMENT_SETTLED',
            'status'      => 'BOOKED',
        ]);
    }
}
