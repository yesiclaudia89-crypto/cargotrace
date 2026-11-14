<?php

namespace Tests\Feature;

use App\Models\Carrier;
use App\Models\Location;
use App\Models\Shipment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShipmentStateMachineTest extends TestCase
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
            'name'          => 'Meratus Line',
            'code'          => 'MRTS',
            'contact_email' => 'ops@meratus.test',
            'is_active'     => true,
        ]);

        $this->origin = Location::create([
            'name'      => 'Tanjung Perak',
            'code'      => 'IDSBY',
            'city'      => 'Surabaya',
            'country'   => 'Indonesia',
            'latitude'  => -7.20,
            'longitude' => 112.73,
        ]);

        $this->destination = Location::create([
            'name'      => 'Port of Rotterdam',
            'code'      => 'NLRTM',
            'city'      => 'Rotterdam',
            'country'   => 'Netherlands',
            'latitude'  => 51.95,
            'longitude' => 4.13,
        ]);
    }

    private function createShipment(string $status = 'CREATED'): Shipment
    {
        $customer = User::factory()->create(['role' => 'customer']);

        return Shipment::create([
            'shipment_number'    => 'CT-STATEMACHINE-' . uniqid(),
            'user_id'            => $customer->id,
            'carrier_id'         => $this->carrier->id,
            'origin_id'          => $this->origin->id,
            'destination_id'     => $this->destination->id,
            'cargo_type'         => 'Coffee Beans 40ft',
            'total_weight'       => 22000,
            'freight_cost'       => 35000000,
            'status'             => $status,
            'payment_status'     => 'PAID',
            'expected_departure' => now(),
            'expected_arrival'   => now()->addDays(14),
            'estimated_arrival'  => now()->addDays(14),
        ]);
    }

    /**
     * Test Slide 6: Valid sequential transitions through full shipment lifecycle.
     */
    public function test_can_transition_through_full_valid_lifecycle(): void
    {
        $shipment = $this->createShipment('CREATED');

        $this->assertTrue($shipment->canTransitionTo('BOOKED'));
        $shipment->status = 'BOOKED';

        $this->assertTrue($shipment->canTransitionTo('PICKED_UP'));
        $shipment->status = 'PICKED_UP';

        $this->assertTrue($shipment->canTransitionTo('IN_TRANSIT'));
        $shipment->status = 'IN_TRANSIT';

        $this->assertTrue($shipment->canTransitionTo('DELIVERED'));
        $shipment->status = 'DELIVERED';

        // Terminal state: cannot transition anywhere once DELIVERED
        $this->assertFalse($shipment->canTransitionTo('CREATED'));
        $this->assertFalse($shipment->canTransitionTo('IN_TRANSIT'));
        $this->assertFalse($shipment->canTransitionTo('CANCELLED'));
    }

    /**
     * Test Slide 6 & 16: Invalid status jump (CREATED -> DELIVERED) rejected via API with 422.
     */
    public function test_invalid_status_transition_is_rejected_via_api(): void
    {
        $shipment = $this->createShipment('CREATED');

        $response = $this->actingAs($this->operator, 'sanctum')->postJson("/api/shipments/{$shipment->id}/tracking", [
            'status'        => 'DELIVERED', // Illegal jump from CREATED!
            'event_type'    => 'CARGO_DELIVERED',
            'location_name' => 'Port of Rotterdam',
            'description'   => 'Trying to deliver directly without picking up.',
            'event_time'    => now()->toDateTimeString(),
        ]);

        $response->assertStatus(422);
        $response->assertJson([
            'success' => false,
        ]);
        $response->assertJsonFragment([
            'message' => "Transisi status invalid! Status saat ini 'CREATED' tidak dapat langsung diubah ke 'DELIVERED'.",
        ]);

        // Verify status in database remains CREATED
        $this->assertEquals('CREATED', $shipment->fresh()->status);
    }

    /**
     * Test Slide 6: Cancellation rules (allowed from CREATED and BOOKED, not from DELIVERED).
     */
    public function test_cancellation_rules(): void
    {
        $created = $this->createShipment('CREATED');
        $this->assertTrue($created->canTransitionTo('CANCELLED'));

        $booked = $this->createShipment('BOOKED');
        $this->assertTrue($booked->canTransitionTo('CANCELLED'));

        $delivered = $this->createShipment('DELIVERED');
        $this->assertFalse($delivered->canTransitionTo('CANCELLED'));
    }
}
