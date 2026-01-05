<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\Product;
use App\Models\Transfer;
use App\Models\Sale;
use App\Models\Location;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;


class InventoryController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Base query
        $query = Inventory::with(['location', 'product']);

        // Filter by location if not owner
        if (!$user->isOwner()) {
            $query->where('location_id', $user->location_id);
        }

        // Apply filters
        if ($request->has('location_id') && $request->location_id) {
            $query->where('location_id', $request->location_id);
        }

        if ($request->has('product_id') && $request->product_id) {
            $query->where('product_id', $request->product_id);
        }

        if ($request->has('low_stock') && $request->low_stock) {
            $query->whereRaw('boxes < (SELECT min_stock_level FROM products WHERE products.id = inventory.product_id)');
        }

        $inventory = $query->paginate(20);

        // Get filter options
        $locations = $user->isOwner() 
            ? Location::active()->get() 
            : Location::where('id', $user->location_id)->get();
        
        $products = Product::active()->get();

        return view('inventory.index', compact('inventory', 'locations', 'products'));
    }

    public function show($id)
    {
        $inventory = Inventory::with(['location', 'product'])->findOrFail($id);
        
        // Check permission
        ///$user = auth()->user();
        $user = Auth::user();
        if (!$user->isOwner() && $inventory->location_id != $user->location_id) {
            abort(403, 'Unauthorized access');
        }

        // Get recent activity for this product at this location
        $recentActivity = $this->getRecentActivity($inventory);

        return view('inventory.show', compact('inventory', 'recentActivity'));
    }

    public function update(Request $request, $id)
    {
        $inventory = Inventory::findOrFail($id);
        $user = Auth::user();
        //$user = auth()->user();

        // Only owner and warehouse manager can adjust inventory manually
        if (!$user->isOwner() && !$user->isWarehouseManager()) {
            return redirect()->back()->with('error', 'Unauthorized action');
        }

        $request->validate([
            'boxes' => 'required|integer|min:0',
            'loose_units' => 'nullable|integer|min:0',
            'reason' => 'required|string|max:255',
        ]);

        $oldBoxes = $inventory->boxes;
        $oldLooseUnits = $inventory->loose_units;

        $inventory->update([
            'boxes' => $request->boxes,
            'loose_units' => $request->loose_units ?? 0,
        ]);

        // Log the adjustment
        ActivityLog::log('inventory_adjusted', [
            'table_name' => 'inventory',
            'record_id' => $inventory->id,
            'details' => "Manual adjustment: {$request->reason}",
            'old_values' => [
                'boxes' => $oldBoxes,
                'loose_units' => $oldLooseUnits
            ],
            'new_values' => [
                'boxes' => $inventory->boxes,
                'loose_units' => $inventory->loose_units
            ]
        ]);

        return redirect()->back()->with('success', 'Inventory updated successfully');
    }

    public function openBox(Request $request, $id)
    {
        $inventory = Inventory::with('product')->findOrFail($id);
        $user = Auth::user();
       // $user = auth()->user();

        // Check permission
        if (!$user->isOwner() && $inventory->location_id != $user->location_id) {
            return redirect()->back()->with('error', 'Unauthorized action');
        }

        if ($inventory->boxes < 1) {
            return redirect()->back()->with('error', 'No boxes available to open');
        }

        $request->validate([
            'reason' => 'required|string|max:255',
        ]);

        DB::beginTransaction();
        try {
            $unitsPerBox = $inventory->product->units_per_box;
            $inventory->openBox();

            // Log the action
            ActivityLog::log('box_opened', [
                'table_name' => 'inventory',
                'record_id' => $inventory->id,
                'details' => "Box opened: {$request->reason}. Added {$unitsPerBox} loose units."
            ]);

            DB::commit();
            return redirect()->back()->with('success', "Box opened successfully. {$unitsPerBox} units added to loose inventory.");
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to open box: ' . $e->getMessage());
        }
    }

    private function getRecentActivity($inventory)
    {
        // Get recent transfers
        $transfers = Transfer::where('product_id', $inventory->product_id)
            ->where(function($q) use ($inventory) {
                $q->where('from_location_id', $inventory->location_id)
                  ->orWhere('to_location_id', $inventory->location_id);
            })
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Get recent sales
        $sales = Sale::where('location_id', $inventory->location_id)
            ->where('product_id', $inventory->product_id)
            ->orderBy('sold_at', 'desc')
            ->take(5)
            ->get();

        return [
            'transfers' => $transfers,
            'sales' => $sales,
        ];
    }
}
