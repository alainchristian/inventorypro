<?php

// FILE: app/Http/Controllers/DashboardController.php

namespace App\Http\Controllers;

use App\Models\Location;
use App\Models\Product;
use App\Models\Inventory;
use App\Models\Transfer;
use App\Models\Sale;
use App\Models\Complaint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;


use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Date filters with default to current month
        $startDate = $request->input('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->endOfMonth()->format('Y-m-d'));
        
        // Get locations based on user role
        if ($user->isOwner()) {
            $locations = Location::active()->get();
        } else {
            $locations = Location::where('id', $user->location_id)->get();
        }

        // === SALES ANALYTICS ===
        $salesData = $this->getSalesAnalytics($user, $startDate, $endDate);
        
        // === INVENTORY STATUS ===
        $inventoryData = $this->getInventoryStatus($user);
        
        // === DAMAGED ITEMS ===
        $damagedItems = $this->getDamagedItems($user);
        
        // === PENDING REQUESTS ===
        $pendingRequests = $this->getPendingRequests($user);
        
        // === QUICK STATS ===
        $stats = [
            'total_locations' => $locations->count(),
            'pending_requests' => $pendingRequests->count(),
            'low_stock_items' => $this->getLowStockCount($user),
           // 'total_damaged_units' => $damagedItems->sum('damaged_units'),
            'total_damaged_units' => $damagedItems['total_damaged_units'],
        ];

        return view('dashboard.index', compact(
            'stats',
            'locations',
            'salesData',
            'inventoryData',
            'damagedItems',
            'pendingRequests',
            'startDate',
            'endDate'
        ));
    }

    /**
     * Get comprehensive sales analytics
     */
    private function getSalesAnalytics($user, $startDate, $endDate)
    {
        $query = Sale::with(['location', 'product', 'seller'])
            ->whereBetween('sold_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);

        if (!$user->isOwner()) {
            $query->where('location_id', $user->location_id);
        }

        $sales = $query->get();

        // Total sales amount for all locations
        $totalSales = $sales->sum('total_amount');
        $totalBoxes = $sales->sum('quantity');

        // Sales by location
        $salesByLocation = $sales->groupBy('location_id')->map(function($locationSales) {
            $location = $locationSales->first()->location;
            return [
                'location_id' => $location->id,
                'location_name' => $location->name,
                'total_amount' => $locationSales->sum('total_amount'),
                'total_boxes' => $locationSales->sum('quantity'),
                'total_transactions' => $locationSales->count(),
            ];
        })->values();

        // Sales by product (across all locations)
        $salesByProduct = $sales->groupBy('product_id')->map(function($productSales) {
            $product = $productSales->first()->product;
            
            // Group by location for this product
            $locationBreakdown = $productSales->groupBy('location_id')->map(function($locSales) {
                return [
                    'location_name' => $locSales->first()->location->name,
                    'boxes_sold' => $locSales->sum('quantity'),
                    'amount' => $locSales->sum('total_amount'),
                ];
            });

            return [
                'product_id' => $product->id,
                'product_name' => $product->name,
                'total_boxes' => $productSales->sum('quantity'),
                'total_amount' => $productSales->sum('total_amount'),
                'locations' => $locationBreakdown,
            ];
        })->sortByDesc('total_amount')->values();

        // Daily sales trend
        $dailySales = $sales->groupBy(function($sale) {
            return Carbon::parse($sale->sold_at)->format('Y-m-d');
        })->map(function($daySales, $date) {
            return [
                'date' => $date,
                'amount' => $daySales->sum('total_amount'),
                'boxes' => $daySales->sum('quantity'),
                'transactions' => $daySales->count(),
            ];
        })->values();

        return [
            'total_sales' => $totalSales,
            'total_boxes' => $totalBoxes,
            'total_transactions' => $sales->count(),
            'by_location' => $salesByLocation,
            'by_product' => $salesByProduct,
            'daily_trend' => $dailySales,
        ];
    }

    /**
     * Get inventory status for all locations
     */
    private function getInventoryStatus($user)
    {
        $query = Inventory::with(['location', 'product']);

        if (!$user->isOwner()) {
            $query->where('location_id', $user->location_id);
        }

        $inventory = $query->get();

        // Group by location
        $inventoryByLocation = $inventory->groupBy('location_id')->map(function($locationInv) {
            $location = $locationInv->first()->location;
            
            // Detailed products in this location
            $products = $locationInv->map(function($inv) {
                return [
                    'product_id' => $inv->product_id,
                    'product_name' => $inv->product->name,
                    'boxes' => $inv->boxes,
                    'loose_units' => $inv->loose_units,
                    'units_per_box' => $inv->product->units_per_box,
                    'total_units' => ($inv->boxes * $inv->product->units_per_box) + $inv->loose_units,
                    'is_low_stock' => $inv->is_low_stock,
                ];
            });

            return [
                'location_id' => $location->id,
                'location_name' => $location->name,
                'location_type' => $location->type,
                'total_boxes' => $locationInv->sum('boxes'),
                'total_loose_units' => $locationInv->sum('loose_units'),
                'unique_products' => $locationInv->count(),
                'low_stock_count' => $locationInv->filter(fn($inv) => $inv->is_low_stock)->count(),
                'products' => $products,
            ];
        })->values();

        // Group by product (showing which location has what)
        $inventoryByProduct = $inventory->groupBy('product_id')->map(function($productInv) {
            $product = $productInv->first()->product;
            
            $locationBreakdown = $productInv->map(function($inv) {
                return [
                    'location_name' => $inv->location->name,
                    'boxes' => $inv->boxes,
                    'loose_units' => $inv->loose_units,
                    'total_units' => ($inv->boxes * $inv->product->units_per_box) + $inv->loose_units,
                ];
            });

            return [
                'product_id' => $product->id,
                'product_name' => $product->name,
                'units_per_box' => $product->units_per_box,
                'total_boxes' => $productInv->sum('boxes'),
                'total_loose_units' => $productInv->sum('loose_units'),
                'locations' => $locationBreakdown,
            ];
        })->values();

        return [
            'by_location' => $inventoryByLocation,
            'by_product' => $inventoryByProduct,
        ];
    }

    /**
     * Get damaged items per location and per product
     */
    private function getDamagedItems($user)
    {
        $query = Inventory::with(['location', 'product'])
            ->where('damaged_units', '>', 0);

        if (!$user->isOwner()) {
            $query->where('location_id', $user->location_id);
        }

        $damagedInventory = $query->get();

        // Group by location
        $damagedByLocation = $damagedInventory->groupBy('location_id')->map(function($locationDamaged) {
            $location = $locationDamaged->first()->location;
            
            $products = $locationDamaged->map(function($inv) {
                return [
                    'product_id' => $inv->product_id,
                    'product_name' => $inv->product->name,
                    'damaged_units' => $inv->damaged_units,
                ];
            });

            return [
                'location_name' => $location->name,
                'total_damaged' => $locationDamaged->sum('damaged_units'),
                'products' => $products,
            ];
        })->values();

        // Also get complaints for additional context
        $complaintsQuery = Complaint::with(['location', 'product'])
            ->where('status', '!=', 'closed');

        if (!$user->isOwner()) {
            $complaintsQuery->where('location_id', $user->location_id);
        }

        $activeComplaints = $complaintsQuery->get();

        return [
            'inventory_damaged' => $damagedByLocation,
            'total_damaged_units' => $damagedInventory->sum('damaged_units'),
            'active_complaints' => $activeComplaints->count(),
        ];
    }

    /**
     * Get pending requests (transfers)
     */
    private function getPendingRequests($user)
    {
        $query = Transfer::with(['fromLocation', 'toLocation', 'product', 'requester'])
            ->whereIn('status', ['pending', 'in_transit'])
            ->orderBy('requested_at', 'desc');

        if (!$user->isOwner()) {
            $query->where(function($q) use ($user) {
                $q->where('from_location_id', $user->location_id)
                  ->orWhere('to_location_id', $user->location_id);
            });
        }

        return $query->get();
    }

    /**
     * Get low stock count
     */
    private function getLowStockCount($user)
    {
        $query = Inventory::whereRaw('boxes < (SELECT min_stock_level FROM products WHERE products.id = inventory.product_id)');

        if (!$user->isOwner()) {
            $query->where('location_id', $user->location_id);
        }

        return $query->count();
    }

    /**
     * Export data as Excel/PDF
     */
    public function export(Request $request, $type)
    {
        // This will be implemented with Laravel Excel or DomPDF
        $startDate = $request->input('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->endOfMonth()->format('Y-m-d'));
        
        // Return download based on type
        return redirect()->back()->with('info', 'Export functionality will be implemented');
    }
}