<?php

namespace App\Http\Controllers;

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Product;
use App\Models\Location;
use App\Models\Inventory;
use App\Models\Transfer;
use App\Models\Complaint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index()
    {
        return view('reports.index');
    }

    public function sales(Request $request)
    {
        $user = Auth::user();
        
        $startDate = $request->input('start_date', now()->startOfMonth());
        $endDate = $request->input('end_date', now()->endOfMonth());

        $query = Sale::with(['location', 'product', 'seller'])
            ->whereBetween('sold_at', [$startDate, $endDate]);

        if (!$user->isOwner()) {
            $query->where('location_id', $user->location_id);
        }

        $sales = $query->get();

        // Calculate summary
        $summary = [
            'total_sales' => $sales->sum('total_amount'),
            'total_boxes' => $sales->sum('quantity'),
            'total_transactions' => $sales->count(),
            'average_sale' => $sales->avg('total_amount'),
        ];

        // Sales by location
        $salesByLocation = $sales->groupBy('location_id')->map(function($locationSales) {
            return [
                'location' => $locationSales->first()->location->name,
                'total' => $locationSales->sum('total_amount'),
                'boxes' => $locationSales->sum('quantity'),
                'count' => $locationSales->count(),
            ];
        });

        // Sales by product
        $salesByProduct = $sales->groupBy('product_id')->map(function($productSales) {
            return [
                'product' => $productSales->first()->product->name,
                'total' => $productSales->sum('total_amount'),
                'boxes' => $productSales->sum('quantity'),
                'count' => $productSales->count(),
            ];
        })->sortByDesc('total');

        // Daily sales trend
        $dailySales = $sales->groupBy(function($sale) {
            return $sale->sold_at->format('Y-m-d');
        })->map(function($daySales) {
            return $daySales->sum('total_amount');
        });

        // Calculate profit (only for owner)
        $totalProfit = 0;
        if ($user->canViewPricing()) {
            $totalProfit = $sales->sum(function($sale) {
                return $sale->calculateProfit();
            });
            $summary['total_profit'] = $totalProfit;
            $summary['profit_margin'] = $summary['total_sales'] > 0 
                ? ($totalProfit / $summary['total_sales']) * 100 
                : 0;
        }

        return view('reports.sales', compact(
            'sales',
            'summary',
            'salesByLocation',
            'salesByProduct',
            'dailySales',
            'startDate',
            'endDate'
        ));
    }

    public function inventory(Request $request)
    {
        //$user = auth()->user();
        $user = Auth::user();

        
        $query = Inventory::with(['location', 'product']);

        if (!$user->isOwner()) {
            $query->where('location_id', $user->location_id);
        }

        $inventory = $query->get();

        // Calculate totals
        $summary = [
            'total_boxes' => $inventory->sum('boxes'),
            'total_loose_units' => $inventory->sum('loose_units'),
            'total_damaged_units' => $inventory->sum('damaged_units'),
            'low_stock_items' => $inventory->filter(fn($inv) => $inv->is_low_stock)->count(),
        ];

        // Stock value (only for owner)
        if ($user->canViewPricing()) {
            $summary['total_stock_value'] = $inventory->sum('stock_value');
        }

        // Inventory by location
        $inventoryByLocation = $inventory->groupBy('location_id')->map(function($locationInventory) {
            return [
                'location' => $locationInventory->first()->location->name,
                'boxes' => $locationInventory->sum('boxes'),
                'loose_units' => $locationInventory->sum('loose_units'),
                'products' => $locationInventory->count(),
            ];
        });

        // Low stock products
        $lowStockProducts = $inventory->filter(fn($inv) => $inv->is_low_stock);

        return view('reports.inventory', compact(
            'inventory',
            'summary',
            'inventoryByLocation',
            'lowStockProducts'
        ));
    }

    public function transfers(Request $request)
    {
        $user = Auth::user();

        //$user = auth()->user();
        
        $startDate = $request->input('start_date', now()->startOfMonth());
        $endDate = $request->input('end_date', now()->endOfMonth());

        $query = Transfer::with(['fromLocation', 'toLocation', 'product', 'requester'])
            ->whereBetween('requested_at', [$startDate, $endDate]);

        if (!$user->isOwner()) {
            $query->where(function($q) use ($user) {
                $q->where('from_location_id', $user->location_id)
                  ->orWhere('to_location_id', $user->location_id);
            });
        }

        $transfers = $query->get();

        // Summary
        $summary = [
            'total_transfers' => $transfers->count(),
            'pending' => $transfers->where('status', 'pending')->count(),
            'in_transit' => $transfers->where('status', 'in_transit')->count(),
            'completed' => $transfers->where('status', 'completed')->count(),
            'cancelled' => $transfers->where('status', 'cancelled')->count(),
            'total_boxes_transferred' => $transfers->where('status', 'completed')->sum('quantity'),
        ];

        // Transfers by location
        $transfersByLocation = $transfers->where('status', 'completed')
            ->groupBy('to_location_id')
            ->map(function($locationTransfers) {
                return [
                    'location' => $locationTransfers->first()->toLocation->name,
                    'boxes' => $locationTransfers->sum('quantity'),
                    'count' => $locationTransfers->count(),
                ];
            });

        return view('reports.transfers', compact(
            'transfers',
            'summary',
            'transfersByLocation',
            'startDate',
            'endDate'
        ));
    }

    public function complaints(Request $request)
    {
        //$user = auth()->user();
        $user = Auth::user();

        
        $query = Complaint::with(['location', 'product', 'reporter']);

        if (!$user->isOwner()) {
            $query->where('location_id', $user->location_id);
        }

        $complaints = $query->get();

        // Summary
        $summary = [
            'total_complaints' => $complaints->count(),
            'pending' => $complaints->where('status', 'pending')->count(),
            'resolved' => $complaints->where('status', 'resolved')->count(),
            'supplier_notified' => $complaints->where('status', 'supplier_notified')->count(),
            'total_damaged_units' => $complaints->sum('damaged_units'),
            'boxes_opened' => $complaints->sum('box_opened'),
        ];

        // Complaints by product
        $complaintsByProduct = $complaints->groupBy('product_id')->map(function($productComplaints) {
            return [
                'product' => $productComplaints->first()->product->name,
                'count' => $productComplaints->count(),
                'damaged_units' => $productComplaints->sum('damaged_units'),
            ];
        })->sortByDesc('count');

        return view('reports.complaints', compact(
            'complaints',
            'summary',
            'complaintsByProduct'
        ));
    }

    public function export(Request $request, $type)
    {
        // This method would handle CSV/PDF exports
        // Implementation depends on your preferred export library
        // (e.g., Laravel Excel, Barryvdh/DomPDF)
        
        return redirect()->back()->with('info', 'Export functionality coming soon');
    }
}
