<?php

namespace Tests\Feature;

use App\Models\Carrier;
use App\Models\Location;
use App\Models\Shipment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShipmentAuthorizationTest extends TestCase
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

    private function createShipmentFor(User $user, string $number = 'CT-TEST-001'): Shipment
    {
        return Shipment::create([
            'shipment_number'    => $number,
            'user_id'            => $user->id,
            'carrier_id'         => $this->carrier->id,
            'origin_id'          => $this->origin->id,
            'destination_id'     => $this->destination->id,
            'cargo_type'         => 'Electronics 20ft',
            'total_weight'       => 10000,
            'freight_cost'       => 15000000,
            'status'             => 'CREATED',
            'payment_status'     => 'UNPAID',
            'expected_departure' => now(),
            'expected_arrival'   => now()->addDays(3),
            'estimated_arrival'  => now()->addDays(3),
        ]);
    }

    /**
     * Test Slide 5 & 13: Customer A cannot view Customer B's shipment on Web (403 Forbidden).
     */
    public function test_customer_cannot_view_other_customers_shipment_on_web(): void
    {
        $customerA = User::factory()->create(['role' => 'customer']);
        $customerB = User::factory()->create(['role' => 'customer']);

        $shipmentB = $this->createShipmentFor($customerB, 'CT-CUST-B');

        $response = $this->actingAs($customerA)->get(route('shipments.show', $shipmentB));

        $response->assertStatus(403);
    }

    /**
     * Test Slide 5 & 13: Customer can view their own shipment on Web (200 OK).
     */
    public function test_customer_can_view_own_shipment_on_web(): void
    {
        $customerA = User::factory()->create(['role' => 'customer']);
        $shipmentA = $this->createShipmentFor($customerA, 'CT-CUST-A');

        $response = $this->actingAs($customerA)->get(route('shipments.show', $shipmentA));

        $response->assertStatus(200);
        $response->assertSee('CT-CUST-A');
    }

    /**
     * Test Slide 13: Customer cannot view other customer's shipment via REST API (403 Forbidden).
     */
    public function test_customer_cannot_view_other_customers_shipment_via_api(): void
    {
        $customerA = User::factory()->create(['role' => 'customer']);
        $customerB = User::factory()->create(['role' => 'customer']);

        $shipmentB = $this->createShipmentFor($customerB, 'CT-API-B');

        $response = $this->actingAs($customerA, 'sanctum')->getJson("/api/shipments/{$shipmentB->id}");

        $response->assertStatus(403);
        $response->assertJson([
            'success' => false,
        ]);
    }

    /**
     * Test Slide 5: Admin and Logistics Operator can view any shipment.
     */
    public function test_admin_and_operator_can_view_any_shipment(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $operator = User::factory()->create(['role' => 'operator']);
        $customer = User::factory()->create(['role' => 'customer']);

        $shipment = $this->createShipmentFor($customer, 'CT-SHARED');

        // Admin
        $this->actingAs($admin)->get(route('shipments.show', $shipment))->assertStatus(200);

        // Operator
        $this->actingAs($operator)->get(route('shipments.show', $shipment))->assertStatus(200);
    }

    /**
     * Test Slide 5: Customer cannot create carriers or locations (Admin only).
     */
    public function test_customer_cannot_create_master_data(): void
    {
        $customer = User::factory()->create(['role' => 'customer']);

        $response = $this->actingAs($customer)->post(route('carriers.store'), [
            'name' => 'Unauthorized Carrier',
            'code' => 'UNAUTH',
        ]);

        $response->assertStatus(403);
    }
}
