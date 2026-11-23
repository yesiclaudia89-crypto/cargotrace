<?php

namespace Tests\Feature;

use App\Models\Carrier;
use App\Models\Location;
use App\Models\Shipment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class PublicTrackingTest extends TestCase
{
    use RefreshDatabase;

    private Carrier $carrier;
    private Location $origin;
    private Location $destination;
    private User $customer;

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

        $this->customer = User::create([
            'name'     => 'PT Budi Sejahtera',
            'email'    => 'budi@sejahtera.test',
            'password' => Hash::make('password'),
            'role'     => 'customer',
        ]);
    }

    public function test_public_track_page_loads_successfully_with_query_parameter(): void
    {
        Shipment::create([
            'shipment_number'    => 'CT-2026-001',
            'carrier_id'         => $this->carrier->id,
            'origin_id'          => $this->origin->id,
            'destination_id'     => $this->destination->id,
            'user_id'            => $this->customer->id,
            'status'             => 'IN_TRANSIT',
            'cargo_type'         => 'Dry Container 40ft',
            'weight_kg'          => 12000,
            'volume_cbm'         => 65,
            'freight_cost'       => 25000000,
            'payment_status'     => 'PAID',
            'expected_departure' => now()->subDays(2),
            'expected_arrival'   => now()->addDays(3),
            'estimated_arrival'  => now()->addDays(3),
        ]);

        $response = $this->get('/track?tracking_number=CT-2026-001');

        $response->assertStatus(200);
        $response->assertSee('CT-2026-001');
        $response->assertSee('Visualisasi 3D Lintasan Maritim');
        $response->assertSee('Sensor Cuaca Maritim Terkini');
    }

    public function test_public_track_page_loads_with_route_parameter(): void
    {
        Shipment::create([
            'shipment_number'    => 'CT-2026-002',
            'carrier_id'         => $this->carrier->id,
            'origin_id'          => $this->origin->id,
            'destination_id'     => $this->destination->id,
            'user_id'            => $this->customer->id,
            'status'             => 'DELIVERED',
            'cargo_type'         => 'Reefer 20ft',
            'weight_kg'          => 8000,
            'volume_cbm'         => 30,
            'freight_cost'       => 18000000,
            'payment_status'     => 'PAID',
            'expected_departure' => now()->subDays(5),
            'expected_arrival'   => now()->subDays(1),
            'estimated_arrival'  => now()->subDays(1),
        ]);

        $response = $this->get('/track/CT-2026-002');

        $response->assertStatus(200);
        $response->assertSee('CT-2026-002');
    }

    public function test_public_track_page_falls_back_when_query_not_found(): void
    {
        Shipment::create([
            'shipment_number'    => 'CT-2026-001',
            'carrier_id'         => $this->carrier->id,
            'origin_id'          => $this->origin->id,
            'destination_id'     => $this->destination->id,
            'user_id'            => $this->customer->id,
            'status'             => 'CREATED',
            'cargo_type'         => 'Dry Container 20ft',
            'weight_kg'          => 5000,
            'volume_cbm'         => 25,
            'freight_cost'       => 10000000,
            'payment_status'     => 'UNPAID',
            'expected_departure' => now()->addDay(),
            'expected_arrival'   => now()->addDays(4),
            'estimated_arrival'  => now()->addDays(4),
        ]);

        $response = $this->get('/track?tracking_number=UNKNOWN-999');

        $response->assertStatus(200);
        $response->assertSee('CT-2026-001');
    }
}
