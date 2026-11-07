<?php

namespace Database\Seeders;

use App\Models\Carrier;
use App\Models\Location;
use App\Models\User;
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
    }
}
