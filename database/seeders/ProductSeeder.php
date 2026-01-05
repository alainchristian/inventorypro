<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // Based on the Excel file provided
        $products = [
            [
                'id' => 'HUA-FB-SE2',
                'name' => 'HUA Freebuds SE 2',
                'description' => 'Huawei Freebuds SE 2 - Wireless Earbuds',
                'units_per_box' => 10, // Adjust based on your actual packaging
                'purchase_price' => 45000,
                'selling_price' => 65000,
                'min_stock_level' => 5,
                'supplier_name' => 'Huawei Official Distributor',
                'supplier_phone' => '+250788000001',
                'barcode' => 'HUA001',
                'is_active' => true,
            ],
            [
                'id' => 'HUA-FB-6I',
                'name' => 'HUA Freebuds 6i',
                'description' => 'Huawei Freebuds 6i - Premium Wireless Earbuds',
                'units_per_box' => 10,
                'purchase_price' => 75000,
                'selling_price' => 110000,
                'min_stock_level' => 5,
                'supplier_name' => 'Huawei Official Distributor',
                'supplier_phone' => '+250788000001',
                'barcode' => 'HUA002',
                'is_active' => true,
            ],
            [
                'id' => 'HUA-FB-5I',
                'name' => 'HUA Freebuds 5i',
                'description' => 'Huawei Freebuds 5i - Wireless Earbuds with Active Noise Cancellation',
                'units_per_box' => 10,
                'purchase_price' => 70000,
                'selling_price' => 105000,
                'min_stock_level' => 5,
                'supplier_name' => 'Huawei Official Distributor',
                'supplier_phone' => '+250788000001',
                'barcode' => 'HUA003',
                'is_active' => true,
            ],
            [
                'id' => 'HUA-FB-5',
                'name' => 'HUA Freebuds 5',
                'description' => 'Huawei Freebuds 5 - Advanced Wireless Earbuds',
                'units_per_box' => 10,
                'purchase_price' => 85000,
                'selling_price' => 125000,
                'min_stock_level' => 5,
                'supplier_name' => 'Huawei Official Distributor',
                'supplier_phone' => '+250788000001',
                'barcode' => 'HUA004',
                'is_active' => true,
            ],
            [
                'id' => 'HUA-FB-PRO3',
                'name' => 'HUA Freebuds Pro 3',
                'description' => 'Huawei Freebuds Pro 3 - Professional Wireless Earbuds',
                'units_per_box' => 8,
                'purchase_price' => 120000,
                'selling_price' => 175000,
                'min_stock_level' => 3,
                'supplier_name' => 'Huawei Official Distributor',
                'supplier_phone' => '+250788000001',
                'barcode' => 'HUA005',
                'is_active' => true,
            ],
            [
                'id' => 'HUA-WATCH-FIT3',
                'name' => 'HUA Watch Fit 3',
                'description' => 'Huawei Watch Fit 3 - Fitness Smartwatch',
                'units_per_box' => 8,
                'purchase_price' => 95000,
                'selling_price' => 140000,
                'min_stock_level' => 4,
                'supplier_name' => 'Huawei Official Distributor',
                'supplier_phone' => '+250788000001',
                'barcode' => 'HUA006',
                'is_active' => true,
            ],
            [
                'id' => 'HUA-WATCH-4-PRO',
                'name' => 'HUA Watch 4 Pro',
                'description' => 'Huawei Watch 4 Pro - Premium Smartwatch',
                'units_per_box' => 6,
                'purchase_price' => 180000,
                'selling_price' => 260000,
                'min_stock_level' => 3,
                'supplier_name' => 'Huawei Official Distributor',
                'supplier_phone' => '+250788000001',
                'barcode' => 'HUA007',
                'is_active' => true,
            ],
            [
                'id' => 'HUA-BAND-9',
                'name' => 'HUA Band 9',
                'description' => 'Huawei Band 9 - Smart Fitness Band',
                'units_per_box' => 12,
                'purchase_price' => 35000,
                'selling_price' => 52000,
                'min_stock_level' => 8,
                'supplier_name' => 'Huawei Official Distributor',
                'supplier_phone' => '+250788000001',
                'barcode' => 'HUA008',
                'is_active' => true,
            ],
            [
                'id' => 'HUA-WATCH-GT4',
                'name' => 'HUA Watch GT 4',
                'description' => 'Huawei Watch GT 4 - Sport & Health Smartwatch',
                'units_per_box' => 6,
                'purchase_price' => 150000,
                'selling_price' => 220000,
                'min_stock_level' => 3,
                'supplier_name' => 'Huawei Official Distributor',
                'supplier_phone' => '+250788000001',
                'barcode' => 'HUA009',
                'is_active' => true,
            ],
            [
                'id' => 'HUA-FB-PRO2',
                'name' => 'HUA Freebuds Pro 2',
                'description' => 'Huawei Freebuds Pro 2 - Previous Gen Pro Earbuds',
                'units_per_box' => 8,
                'purchase_price' => 100000,
                'selling_price' => 150000,
                'min_stock_level' => 4,
                'supplier_name' => 'Huawei Official Distributor',
                'supplier_phone' => '+250788000001',
                'barcode' => 'HUA010',
                'is_active' => true,
            ],

            // Add some additional common products
            [
                'id' => 'ACC-CHG-65W',
                'name' => 'Fast Charger 65W',
                'description' => 'Universal 65W Fast Charger',
                'units_per_box' => 20,
                'purchase_price' => 15000,
                'selling_price' => 25000,
                'min_stock_level' => 10,
                'supplier_name' => 'Tech Accessories Ltd',
                'supplier_phone' => '+250788000002',
                'barcode' => 'ACC001',
                'is_active' => true,
            ],
            [
                'id' => 'ACC-CABLE-USB',
                'name' => 'USB-C Cable Premium',
                'description' => 'High-quality USB-C charging cable',
                'units_per_box' => 50,
                'purchase_price' => 3000,
                'selling_price' => 5000,
                'min_stock_level' => 20,
                'supplier_name' => 'Tech Accessories Ltd',
                'supplier_phone' => '+250788000002',
                'barcode' => 'ACC002',
                'is_active' => true,
            ],
            [
                'id' => 'ACC-CASE-PHONE',
                'name' => 'Phone Case Universal',
                'description' => 'Universal protective phone case',
                'units_per_box' => 30,
                'purchase_price' => 5000,
                'selling_price' => 8000,
                'min_stock_level' => 15,
                'supplier_name' => 'Tech Accessories Ltd',
                'supplier_phone' => '+250788000002',
                'barcode' => 'ACC003',
                'is_active' => true,
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }

        $this->command->info('Products seeded successfully!');
    }
}