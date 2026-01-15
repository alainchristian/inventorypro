<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Product;
use App\Models\Inventory;
use App\Models\Location;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    /**
     * Display a listing of sales
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        // Base query
        $query = Sale::with(['location', 'product', 'seller']);

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

        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('sold_at', '>=', $request->date_from);
        }

        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('sold_at', '<=', $request->date_to);
        }

        if ($request->has('receipt_number') && $request->receipt_number) {
            $query->where('receipt_number', 'like', '%' . $request->receipt_number . '%');
        }

        // Calculate totals for the filtered results (clone query first)
        $totalSales = (clone $query)->sum('total_amount');
        $totalQuantity = (clone $query)->sum('quantity');

        // Order by latest first and paginate
        $sales = $query->orderBy('sold_at', 'desc')->paginate(20);

        // Get filter options
        $locations = $user->isOwner()
            ? Location::active()->get()
            : Location::where('id', $user->location_id)->get();

        $products = Product::active()->get();

        return view('sales.index', compact('sales', 'locations', 'products', 'totalSales', 'totalQuantity'));
    }

    /**
     * Show the form for creating a new sale
     */
    public function create()
    {
        $user = Auth::user();

        // Check if user can make sales
        if (!$user->canMakeSales()) {
            return redirect()->back()->with('error', 'You do not have permission to make sales.');
        }

        // Get location - use user's location or let them choose if owner
        if ($user->isOwner()) {
            $locations = Location::active()->get();
            $selectedLocation = null;
        } else {
            $locations = Location::where('id', $user->location_id)->get();
            $selectedLocation = $user->location_id;
        }

        // Get active products
        $products = Product::active()->get();

        // Get inventory for the user's location
        $inventory = $selectedLocation
            ? Inventory::where('location_id', $selectedLocation)
                ->with('product')
                ->get()
            : collect();

        return view('sales.create', compact('locations', 'products', 'inventory', 'selectedLocation'));
    }

    /**
     * Store a newly created sale
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        // Check if user can make sales
        if (!$user->canMakeSales()) {
            return redirect()->back()->with('error', 'You do not have permission to make sales.');
        }

        $request->validate([
            'location_id' => 'required|exists:locations,id',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'unit_type' => 'required|in:box,loose',
            'unit_price' => 'required|numeric|min:0',
            'customer_name' => 'nullable|string|max:100',
            'customer_phone' => 'nullable|string|max:20',
            'notes' => 'nullable|string|max:500',
        ]);

        DB::beginTransaction();

        try {
            // Get inventory record
            $inventory = Inventory::where('location_id', $request->location_id)
                ->where('product_id', $request->product_id)
                ->lockForUpdate()
                ->firstOrFail();

            $product = Product::findOrFail($request->product_id);
            $quantity = (int) $request->quantity;
            $unitType = $request->unit_type;

            // Check stock availability and reduce inventory
            if ($unitType === 'box') {
                if ($inventory->boxes < $quantity) {
                    throw new \Exception('Insufficient stock. Available: ' . $inventory->boxes . ' boxes');
                }

                $inventory->boxes -= $quantity;
                $actualQuantity = $quantity; // boxes sold

            } else { // loose units
                if ($inventory->loose_units < $quantity) {
                    throw new \Exception('Insufficient loose units. Available: ' . $inventory->loose_units . ' units');
                }

                $inventory->loose_units -= $quantity;
                $actualQuantity = $quantity; // loose units sold
            }

            $inventory->last_updated = now();
            $inventory->save();

            // Calculate total amount
            $unitPrice = (float) $request->unit_price;
            $totalAmount = $unitPrice * $quantity;

            // Generate receipt number
            $receiptNumber = Sale::generateReceiptNumber();

            // Create sale record
            $sale = Sale::create([
                'location_id' => $request->location_id,
                'product_id' => $request->product_id,
                'quantity' => $actualQuantity,
                'unit_price' => $unitPrice,
                'total_amount' => $totalAmount,
                'sold_by' => $user->id,
                'sold_at' => now(),
                'customer_name' => $request->customer_name,
                'customer_phone' => $request->customer_phone,
                'receipt_number' => $receiptNumber,
                'notes' => $request->notes,
            ]);

            // Log activity
            ActivityLog::log('sale_created', [
                'table_name' => 'sales',
                'record_id' => $sale->id,
                'details' => "Sale recorded: {$quantity} {$unitType}(s) of {$product->name}",
                'new_values' => [
                    'receipt_number' => $receiptNumber,
                    'total_amount' => $totalAmount,
                    'inventory_reduced' => "{$quantity} {$unitType}(s)",
                ],
            ]);

            // Log inventory change
            ActivityLog::log('inventory_updated', [
                'table_name' => 'inventory',
                'record_id' => $inventory->id,
                'details' => "Inventory reduced due to sale #{$receiptNumber}",
                'old_values' => [
                    'boxes' => $unitType === 'box' ? ($inventory->boxes + $quantity) : $inventory->boxes,
                    'loose_units' => $unitType === 'loose' ? ($inventory->loose_units + $quantity) : $inventory->loose_units,
                ],
                'new_values' => [
                    'boxes' => $inventory->boxes,
                    'loose_units' => $inventory->loose_units,
                ],
            ]);

            DB::commit();

            return redirect()->route('sales.show', $sale->id)
                ->with('success', 'Sale recorded successfully! Receipt: ' . $sale->formatted_receipt_number);

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to record sale: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified sale
     */
    public function show($id)
    {
        $user = Auth::user();

        $sale = Sale::with(['location', 'product', 'seller'])->findOrFail($id);

        // Check permission - users can only view sales from their location
        if (!$user->isOwner() && $sale->location_id != $user->location_id) {
            abort(403, 'Unauthorized access');
        }

        // Calculate profit
        $profit = $sale->calculateProfit();

        return view('sales.show', compact('sale', 'profit'));
    }

    /**
     * Display printable receipt for a sale
     */
    public function receipt($id)
    {
        $user = Auth::user();

        $sale = Sale::with(['location', 'product', 'seller'])->findOrFail($id);

        // Check permission
        if (!$user->isOwner() && $sale->location_id != $user->location_id) {
            abort(403, 'Unauthorized access');
        }

        return view('sales.receipt', compact('sale'));
    }
}
