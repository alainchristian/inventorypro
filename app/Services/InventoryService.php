<?php


// FILE: app/Services/InventoryService.php

namespace App\Services;

use App\Models\Inventory;
use App\Models\Product;
use App\Models\ActivityLog;
use App\Models\Location;
use Illuminate\Support\Facades\DB;

class InventoryService
{
    public function getStockLevel($locationId, $productId)
    {
        return Inventory::where('location_id', $locationId)
            ->where('product_id', $productId)
            ->first();
    }

    public function checkAvailability($locationId, $productId, $quantity)
    {
        $inventory = $this->getStockLevel($locationId, $productId);
        return $inventory && $inventory->boxes >= $quantity;
    }

    public function adjustStock($locationId, $productId, $boxes, $looseUnits = 0, $reason = 'Manual adjustment')
    {
        DB::beginTransaction();
        try {
            $inventory = Inventory::firstOrCreate(
                ['location_id' => $locationId, 'product_id' => $productId],
                ['boxes' => 0, 'loose_units' => 0, 'damaged_units' => 0]
            );

            $oldBoxes = $inventory->boxes;
            $oldLooseUnits = $inventory->loose_units;

            $inventory->boxes += $boxes;
            $inventory->loose_units += $looseUnits;
            $inventory->save();

            ActivityLog::log('inventory_adjusted', [
                'table_name' => 'inventory',
                'record_id' => $inventory->id,
                'details' => $reason,
                'old_values' => ['boxes' => $oldBoxes, 'loose_units' => $oldLooseUnits],
                'new_values' => ['boxes' => $inventory->boxes, 'loose_units' => $inventory->loose_units]
            ]);

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            return false;
        }
    }

    public function getLowStockProducts($locationId = null)
    {
        $query = Inventory::with(['location', 'product'])
            ->whereRaw('boxes < (SELECT min_stock_level FROM products WHERE products.id = inventory.product_id)');

        if ($locationId) {
            $query->where('location_id', $locationId);
        }

        return $query->get();
    }

    public function getInventorySummary($locationId = null)
    {
        $query = Inventory::with('product');

        if ($locationId) {
            $query->where('location_id', $locationId);
        }

        $inventory = $query->get();

        return [
            'total_boxes' => $inventory->sum('boxes'),
            'total_loose_units' => $inventory->sum('loose_units'),
            'total_damaged_units' => $inventory->sum('damaged_units'),
            'unique_products' => $inventory->count(),
            'low_stock_count' => $inventory->filter(fn($inv) => $inv->is_low_stock)->count(),
        ];
    }
}