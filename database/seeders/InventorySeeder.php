<?php

namespace Database\Seeders;

use App\Models\Inventory;
use Illuminate\Database\Seeder;

class InventorySeeder extends Seeder
{
    public function run(): void
    {
        $inventories = [
            // WAREHOUSE STOCK (Location ID: 1)
            ['location_id' => 1, 'product_id' => 'HUA-FB-SE2', 'boxes' => 100, 'loose_units' => 0, 'damaged_units' => 0],
            ['location_id' => 1, 'product_id' => 'HUA-FB-6I', 'boxes' => 50, 'loose_units' => 0, 'damaged_units' => 0],
            ['location_id' => 1, 'product_id' => 'HUA-FB-5I', 'boxes' => 60, 'loose_units' => 0, 'damaged_units' => 0],
            ['location_id' => 1, 'product_id' => 'HUA-FB-5', 'boxes' => 40, 'loose_units' => 0, 'damaged_units' => 0],
            ['location_id' => 1, 'product_id' => 'HUA-FB-PRO3', 'boxes' => 30, 'loose_units' => 0, 'damaged_units' => 0],
            ['location_id' => 1, 'product_id' => 'HUA-WATCH-FIT3', 'boxes' => 45, 'loose_units' => 0, 'damaged_units' => 0],
            ['location_id' => 1, 'product_id' => 'HUA-WATCH-4-PRO', 'boxes' => 20, 'loose_units' => 0, 'damaged_units' => 0],
            ['location_id' => 1, 'product_id' => 'HUA-BAND-9', 'boxes' => 80, 'loose_units' => 0, 'damaged_units' => 0],
            ['location_id' => 1, 'product_id' => 'HUA-WATCH-GT4', 'boxes' => 25, 'loose_units' => 0, 'damaged_units' => 0],
            ['location_id' => 1, 'product_id' => 'HUA-FB-PRO2', 'boxes' => 35, 'loose_units' => 0, 'damaged_units' => 0],
            ['location_id' => 1, 'product_id' => 'ACC-CHG-65W', 'boxes' => 150, 'loose_units' => 0, 'damaged_units' => 0],
            ['location_id' => 1, 'product_id' => 'ACC-CABLE-USB', 'boxes' => 200, 'loose_units' => 0, 'damaged_units' => 0],
            ['location_id' => 1, 'product_id' => 'ACC-CASE-PHONE', 'boxes' => 120, 'loose_units' => 0, 'damaged_units' => 0],

            // SHOP 1 STOCK (Location ID: 2)
            ['location_id' => 2, 'product_id' => 'HUA-FB-SE2', 'boxes' => 15, 'loose_units' => 0, 'damaged_units' => 0],
            ['location_id' => 2, 'product_id' => 'HUA-FB-6I', 'boxes' => 8, 'loose_units' => 0, 'damaged_units' => 0],
            ['location_id' => 2, 'product_id' => 'HUA-FB-5I', 'boxes' => 10, 'loose_units' => 0, 'damaged_units' => 0],
            ['location_id' => 2, 'product_id' => 'HUA-WATCH-FIT3', 'boxes' => 6, 'loose_units' => 0, 'damaged_units' => 0],
            ['location_id' => 2, 'product_id' => 'HUA-BAND-9', 'boxes' => 12, 'loose_units' => 0, 'damaged_units' => 0],
            ['location_id' => 2, 'product_id' => 'ACC-CHG-65W', 'boxes' => 20, 'loose_units' => 0, 'damaged_units' => 0],
            ['location_id' => 2, 'product_id' => 'ACC-CABLE-USB', 'boxes' => 30, 'loose_units' => 5, 'damaged_units' => 1],

            // SHOP 2 STOCK (Location ID: 3)
            ['location_id' => 3, 'product_id' => 'HUA-FB-SE2', 'boxes' => 12, 'loose_units' => 0, 'damaged_units' => 0],
            ['location_id' => 3, 'product_id' => 'HUA-FB-PRO3', 'boxes' => 5, 'loose_units' => 0, 'damaged_units' => 0],
            ['location_id' => 3, 'product_id' => 'HUA-WATCH-GT4', 'boxes' => 4, 'loose_units' => 0, 'damaged_units' => 0],
            ['location_id' => 3, 'product_id' => 'HUA-BAND-9', 'boxes' => 10, 'loose_units' => 0, 'damaged_units' => 0],
            ['location_id' => 3, 'product_id' => 'ACC-CHG-65W', 'boxes' => 25, 'loose_units' => 0, 'damaged_units' => 0],
            ['location_id' => 3, 'product_id' => 'ACC-CASE-PHONE', 'boxes' => 18, 'loose_units' => 3, 'damaged_units' => 2],
        ];

        foreach ($inventories as $inventory) {
            Inventory::create($inventory);
        }

        $this->command->info('Inventory seeded successfully!');
    }
}