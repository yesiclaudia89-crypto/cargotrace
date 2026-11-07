<?php

namespace Database\Seeders;

use App\Models\Carrier;
use App\Models\Location;
use App\Models\Shipment;
use App\Models\ShipmentException;
use App\Models\ShipmentItem;
use App\Models\TrackingEvent;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Administrator Demo Account
        User::firstOrCreate(
            ['email' => 'admin@cargotrace.test'],
            [
                'name' => 'Administrator',
                'phone' => '+62811000001',
                'role' => 'admin',
                'password' => bcrypt('password'),
            ]
        );

        // 2. Logistics Operator Demo Account
        User::firstOrCreate(
            ['email' => 'operator@cargotrace.test'],
            [
                'name' => 'Logistics Operator',
                'phone' => '+62811000002',
                'role' => 'operator',
                'password' => bcrypt('password'),
            ]
        );

        // 3. Customer Demo Account
        User::firstOrCreate(
            ['email' => 'customer@cargotrace.test'],
            [
                'name' => 'PT Nusantara Makmur (Customer)',
                'phone' => '+62811000003',
                'role' => 'customer',
                'password' => bcrypt('password'),
            ]
        );

        // 4. Logistics Carriers (Slide 11)
        $carriers = [
            [
                'name' => 'Samudera Indonesia',
                'code' => 'SMDR',
                'contact_email' => 'cargo@samudera.id',
                'contact_phone' => '+62215347788',
                'transport_mode' => 'sea',
                'is_active' => true,
            ],
            [
                'name' => 'Meratus Line',
                'code' => 'MRTS',
                'contact_email' => 'cs@meratusline.com',
                'contact_phone' => '+62313291555',
                'transport_mode' => 'sea',
                'is_active' => true,
            ],
            [
                'name' => 'Maersk Line',
                'code' => 'MAEU',
                'contact_email' => 'contact@maersk.com',
                'contact_phone' => '+4533633363',
                'transport_mode' => 'sea',
                'is_active' => true,
            ],
            [
                'name' => 'Garuda Cargo',
                'code' => 'GIA',
                'contact_email' => 'cargo@garuda-indonesia.com',
                'contact_phone' => '+622125601234',
                'transport_mode' => 'air',
                'is_active' => true,
            ],
        ];

        foreach ($carriers as $carrier) {
            Carrier::firstOrCreate(['code' => $carrier['code']], $carrier);
        }

        // 5. Locations / Ports (Slide 9 & 11) with real coordinates for Leaflet.js
        $locations = [
            [
                'name' => 'Tanjung Priok Port, Jakarta',
                'code' => 'IDTPP',
                'type' => 'seaport',
                'country' => 'Indonesia',
                'city' => 'Jakarta',
                'latitude' => -6.1017000,
                'longitude' => 106.8833000,
            ],
            [
                'name' => 'Port of Singapore',
                'code' => 'SGSIN',
                'type' => 'seaport',
                'country' => 'Singapore',
                'city' => 'Singapore',
                'latitude' => 1.2644000,
                'longitude' => 103.8400000,
            ],
            [
                'name' => 'Port of Rotterdam',
                'code' => 'NLRTM',
                'type' => 'seaport',
                'country' => 'Netherlands',
                'city' => 'Rotterdam',
                'latitude' => 51.9547000,
                'longitude' => 4.1378000,
            ],
            [
                'name' => 'Port of Hamburg',
                'code' => 'DEHAM',
                'type' => 'seaport',
                'country' => 'Germany',
                'city' => 'Hamburg',
                'latitude' => 53.5353000,
                'longitude' => 9.9872000,
            ],
            [
                'name' => 'Soekarno-Hatta Cargo Terminal',
                'code' => 'IDCGK',
                'type' => 'airport',
                'country' => 'Indonesia',
                'city' => 'Tangerang',
                'latitude' => -6.1275000,
                'longitude' => 106.6537000,
            ],
        ];

        foreach ($locations as $loc) {
            Location::firstOrCreate(['code' => $loc['code']], $loc);
        }

        // 6. Realistic Shipments (Slide 4, 6, 8, 11)
        $customer = User::where('role', 'customer')->first();
        $operator = User::where('role', 'operator')->first();
        $priok = Location::where('code', 'IDTPP')->first();
        $singapore = Location::where('code', 'SGSIN')->first();
        $rotterdam = Location::where('code', 'NLRTM')->first();
        $samudera = Carrier::where('code', 'SMDR')->first();
        $maersk = Carrier::where('code', 'MAEU')->first();
        $meratus = Carrier::where('code', 'MRTS')->first();

        // Shipment 1: In Transit with Exception (Weather Delay)
        $s1 = Shipment::firstOrCreate(
            ['shipment_number' => 'CT-2026-001'],
            [
                'user_id' => $customer->id,
                'carrier_id' => $samudera->id,
                'origin_id' => $priok->id,
                'destination_id' => $singapore->id,
                'cargo_type' => 'Container (FCL 20ft)',
                'total_weight' => 14200.00,
                'total_volume' => 33.20,
                'status' => 'IN_TRANSIT',
                'payment_status' => 'PAID',
                'freight_cost' => 15000000.00,
                'payment_reference' => 'MIDTRANS-SETTLEMENT-001',
                'expected_departure' => Carbon::now()->subDays(2),
                'expected_arrival' => Carbon::now()->addHours(12),
                'estimated_arrival' => Carbon::now()->addHours(20), // 8h delay
                'delay_hours' => 8,
                'is_delayed' => true,
                'delay_severity' => 'MEDIUM',
                'notes' => 'Shipment contains export textile machinery.',
            ]
        );

        ShipmentItem::firstOrCreate(
            ['shipment_id' => $s1->id, 'item_name' => 'Industrial Textile Weaving Machine'],
            [
                'quantity' => 2,
                'weight_kg' => 7100.00,
                'dimensions' => '240x180x200 cm',
                'hs_code' => '8448.19.00',
            ]
        );

        // Tracking events for Shipment 1
        $eventsS1 = [
            [
                'shipment_id' => $s1->id,
                'location_id' => $priok->id,
                'created_by' => $customer->id,
                'event_type' => 'ORDER_CREATED',
                'status' => 'CREATED',
                'location_name' => 'Tanjung Priok Port, Jakarta',
                'latitude' => -6.1017000,
                'longitude' => 106.8833000,
                'description' => 'Shipment booking created by customer.',
                'event_time' => Carbon::now()->subDays(3),
            ],
            [
                'shipment_id' => $s1->id,
                'location_id' => $priok->id,
                'created_by' => $operator->id,
                'event_type' => 'PAYMENT_CONFIRMED',
                'status' => 'BOOKED',
                'location_name' => 'Tanjung Priok Port, Jakarta',
                'latitude' => -6.1017000,
                'longitude' => 106.8833000,
                'description' => 'Payment settled via Midtrans Sandbox. Booking confirmed.',
                'event_time' => Carbon::now()->subDays(3)->addHours(2),
            ],
            [
                'shipment_id' => $s1->id,
                'location_id' => $priok->id,
                'created_by' => $operator->id,
                'event_type' => 'CARGO_LOADED',
                'status' => 'PICKED_UP',
                'location_name' => 'Tanjung Priok Container Terminal 1',
                'latitude' => -6.1025000,
                'longitude' => 106.8845000,
                'description' => 'Container loaded onto vessel MV Samudera Perkasa.',
                'event_time' => Carbon::now()->subDays(2),
            ],
            [
                'shipment_id' => $s1->id,
                'location_id' => $priok->id,
                'created_by' => $operator->id,
                'event_type' => 'DEPARTED_ORIGIN',
                'status' => 'IN_TRANSIT',
                'location_name' => 'Java Sea / Karimata Strait',
                'latitude' => -3.2000000,
                'longitude' => 107.5000000,
                'description' => 'Vessel en route to Port of Singapore. Reduced speed due to high swell.',
                'event_time' => Carbon::now()->subHours(14),
            ],
        ];

        foreach ($eventsS1 as $ev) {
            TrackingEvent::firstOrCreate(
                ['shipment_id' => $ev['shipment_id'], 'event_type' => $ev['event_type']],
                $ev
            );
        }

        // Exception for Shipment 1: WEATHER_DELAY
        ShipmentException::firstOrCreate(
            ['shipment_id' => $s1->id, 'exception_type' => 'WEATHER_DELAY'],
            [
                'logged_by' => $operator->id,
                'severity' => 'MEDIUM',
                'status' => 'INVESTIGATING',
                'description' => 'High wave advisory (>2.5m) in Karimata Strait caused 8-hour navigational detour.',
                'resolution_notes' => 'Vessel captain navigated alternate route; revised ETA communicated to consignee.',
                'logged_at' => Carbon::now()->subHours(10),
            ]
        );

        // Shipment 2: Delivered & On-Time
        $s2 = Shipment::firstOrCreate(
            ['shipment_number' => 'CT-2026-002'],
            [
                'user_id' => $customer->id,
                'carrier_id' => $maersk->id,
                'origin_id' => $priok->id,
                'destination_id' => $rotterdam->id,
                'cargo_type' => 'Electronics & Solar Cells',
                'total_weight' => 8600.00,
                'total_volume' => 24.50,
                'status' => 'DELIVERED',
                'payment_status' => 'PAID',
                'freight_cost' => 45000000.00,
                'payment_reference' => 'MIDTRANS-SETTLEMENT-002',
                'expected_departure' => Carbon::now()->subDays(20),
                'expected_arrival' => Carbon::now()->subDays(1),
                'estimated_arrival' => Carbon::now()->subDays(1),
                'delay_hours' => 0,
                'is_delayed' => false,
                'delay_severity' => 'NONE',
                'notes' => 'Delivered in good condition with sign-off POD.',
            ]
        );

        ShipmentItem::firstOrCreate(
            ['shipment_id' => $s2->id, 'item_name' => 'Monocrystalline Solar PV Panels'],
            [
                'quantity' => 120,
                'weight_kg' => 8600.00,
                'dimensions' => '200x100x4 cm',
                'hs_code' => '8541.43.00',
            ]
        );

        TrackingEvent::firstOrCreate(
            ['shipment_id' => $s2->id, 'event_type' => 'CARGO_DELIVERED'],
            [
                'location_id' => $rotterdam->id,
                'created_by' => $operator->id,
                'status' => 'DELIVERED',
                'location_name' => 'Port of Rotterdam, Distripark Maasvlakte',
                'latitude' => 51.9547000,
                'longitude' => 4.1378000,
                'description' => 'Cargo successfully delivered and signed by consignee warehouse.',
                'event_time' => Carbon::now()->subDays(1),
            ]
        );

        // Shipment 3: Created (Waiting for Payment Gateway!)
        Shipment::firstOrCreate(
            ['shipment_number' => 'CT-2026-003'],
            [
                'user_id' => $customer->id,
                'carrier_id' => $meratus->id,
                'origin_id' => $priok->id,
                'destination_id' => $singapore->id,
                'cargo_type' => 'Agricultural Commodity (Robusta Coffee Beans)',
                'total_weight' => 18000.00,
                'total_volume' => 38.00,
                'status' => 'CREATED',
                'payment_status' => 'UNPAID',
                'freight_cost' => 12500000.00,
                'payment_reference' => null,
                'expected_departure' => Carbon::now()->addDays(2),
                'expected_arrival' => Carbon::now()->addDays(5),
                'estimated_arrival' => Carbon::now()->addDays(5),
                'delay_hours' => 0,
                'is_delayed' => false,
                'delay_severity' => 'NONE',
                'notes' => 'Awaiting payment from customer to confirm booking.',
            ]
        );
    }
}
