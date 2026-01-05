<?php

namespace Database\Seeders;

use App\Models\Location;
use Illuminate\Database\Seeder;

class LocationSeeder extends Seeder
{
    public function run(): void
    {
        Location::create([
            'name' => 'Main Warehouse',
            'type' => 'warehouse',
            'manager_name' => 'John Doe',
            'address' => 'Industrial Area, Kigali',
            'phone' => '+250788123456',
            'email' => 'warehouse@inventory.com',
            'is_active' => true,
        ]);

        Location::create([
            'name' => 'Shop 1 - Downtown',
            'type' => 'shop',
            'manager_name' => 'Alice Smith',
            'address' => 'KG 11 Ave, Kigali',
            'phone' => '+250788123457',
            'email' => 'shop1@inventory.com',
            'is_active' => true,
        ]);

        Location::create([
            'name' => 'Shop 2 - Kimironko',
            'type' => 'shop',
            'manager_name' => 'Bob Johnson',
            'address' => 'Kimironko Market, Kigali',
            'phone' => '+250788123458',
            'email' => 'shop2@inventory.com',
            'is_active' => true,
        ]);
    }
}
