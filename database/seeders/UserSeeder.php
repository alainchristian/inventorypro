<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Owner
        User::create([
            'name' => 'System Owner',
            'email' => 'owner@inventory.com',
            'password' => Hash::make('password'),
            'role' => 'owner',
            'location_id' => null,
            'phone' => '+250788000001',
            'is_active' => true,
        ]);

        // Warehouse Manager
        User::create([
            'name' => 'John Doe',
            'email' => 'warehouse@inventory.com',
            'password' => Hash::make('password'),
            'role' => 'warehouse_manager',
            'location_id' => 1, // Main Warehouse
            'phone' => '+250788000002',
            'is_active' => true,
        ]);

        // Shop 1 Manager
        User::create([
            'name' => 'Alice Smith',
            'email' => 'shop1@inventory.com',
            'password' => Hash::make('password'),
            'role' => 'shop_manager',
            'location_id' => 2, // Shop 1
            'phone' => '+250788000003',
            'is_active' => true,
        ]);

        // Shop 2 Manager
        User::create([
            'name' => 'Bob Johnson',
            'email' => 'shop2@inventory.com',
            'password' => Hash::make('password'),
            'role' => 'shop_manager',
            'location_id' => 3, // Shop 2
            'phone' => '+250788000004',
            'is_active' => true,
        ]);

        // Shop 1 Salesperson
        User::create([
            'name' => 'Mary Williams',
            'email' => 'sales1@inventory.com',
            'password' => Hash::make('password'),
            'role' => 'salesperson',
            'location_id' => 2, // Shop 1
            'phone' => '+250788000005',
            'is_active' => true,
        ]);
    }
}

