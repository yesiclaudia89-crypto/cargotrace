<?php

namespace Database\Seeders;

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
    }
}
