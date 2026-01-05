<?php

// FILE: app/Http/Controllers/TransferController.php

namespace App\Http\Controllers;

use App\Models\Transfer;
use App\Models\TransferRequest;
use App\Models\Product;
use App\Models\Location;
use App\Models\Inventory;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class TransferController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Query transfer requests (parent records)
        $query = TransferRequest::with(['fromLocation', 'toLocation', 'requester', 'approver', 'receiver', 'transfers.product'])
            ->orderBy('requested_at', 'desc');

        // Filter by user role
        if (!$user->isOwner()) {
            $query->where(function($q) use ($user) {
                $q->where('from_location_id', $user->location_id)
                  ->orWhere('to_location_id', $user->location_id);
            });
        }

        // Status filter
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        $transferRequests = $query->paginate(15);
        
        // Get statistics
        $stats = [
            'pending' => TransferRequest::pending()->count(),
            'approved' => TransferRequest::where('status', 'approved')->count(),
            'in_transit' => TransferRequest::inTransit()->count(),
            'completed' => TransferRequest::completed()->count(),
        ];

        return view('transfers.index', compact('transferRequests', 'stats'));
    }

    public function create()
    {
        //$user = auth()->user();
        $user = Auth::user();
        
        if (!$user->canRequestTransfers()) {
            return redirect()->back()->with('error', 'Unauthorized action');
        }

        $warehouse = Location::where('type', 'warehouse')->where('is_active', true)->first();
        
        if (!$warehouse) {
            return redirect()->back()->with('error', 'No active warehouse found');
        }

        $warehouseInventory = Inventory::with('product')
            ->where('location_id', $warehouse->id)
            ->where('boxes', '>', 0)
            ->whereHas('product', function($q) {
                $q->where('is_active', true);
            })
            ->get()
            ->map(function($inv) {
                return [
                    'product_id' => $inv->product_id,
                    'product_name' => $inv->product->name,
                    'available_boxes' => $inv->boxes,
                    'units_per_box' => $inv->product->units_per_box,
                    'total_units' => $inv->boxes * $inv->product->units_per_box + $inv->loose_units,
                ];
            });

        return view('transfers.create', compact('warehouse', 'warehouseInventory'));
    }

    public function store(Request $request)
    {
        //$user = auth()->user();
        $user = Auth::user();

        if (!$user->canRequestTransfers()) {
            return redirect()->back()->with('error', 'Unauthorized action');
        }

        $request->validate([
            'products' => 'required|array|min:1',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string|max:1000',
        ], [
            'products.required' => 'Please add at least one product to your request.',
        ]);

        $warehouse = Location::where('type', 'warehouse')->where('is_active', true)->first();
        
        if (!$warehouse) {
            return redirect()->back()->with('error', 'No active warehouse found');
        }

        DB::beginTransaction();
        try {
            // Create parent transfer request
            $transferRequest = TransferRequest::create([
                'request_number' => TransferRequest::generateRequestNumber(),
                'from_location_id' => $warehouse->id,
                'to_location_id' => $user->location_id,
                'status' => 'pending',
                'requested_by' => $user->id,
                'requested_at' => now(),
                'notes' => $request->notes,
            ]);

            $totalBoxes = 0;

            // Create individual transfers for each product
            foreach ($request->products as $productData) {
                $warehouseInventory = Inventory::where('location_id', $warehouse->id)
                    ->where('product_id', $productData['product_id'])
                    ->lockForUpdate()
                    ->first();

                if (!$warehouseInventory || $warehouseInventory->boxes < $productData['quantity']) {
                    DB::rollBack();
                    $product = Product::find($productData['product_id']);
                    return redirect()->back()
                        ->with('error', "Insufficient stock for {$product->name}. Available: " . ($warehouseInventory->boxes ?? 0) . " boxes")
                        ->withInput();
                }

                Transfer::create([
                    'transfer_request_id' => $transferRequest->id,
                    'from_location_id' => $warehouse->id,
                    'to_location_id' => $user->location_id,
                    'product_id' => $productData['product_id'],
                    'quantity' => $productData['quantity'],
                    'status' => 'pending',
                    'requested_by' => $user->id,
                    'requested_at' => now(),
                    'notes' => $request->notes,
                ]);

                $totalBoxes += $productData['quantity'];
            }

            ActivityLog::log('bulk_request_created', [
                'table_name' => 'transfer_requests',
                'record_id' => $transferRequest->id,
                'details' => "Bulk request created: {$transferRequest->request_number} with " . count($request->products) . " products, {$totalBoxes} total boxes"
            ]);

            DB::commit();
            
            return redirect()->route('transfers.index')
                ->with('success', "Request {$transferRequest->request_number} created successfully with " . count($request->products) . " products.");
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Transfer creation error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Failed to create transfer request: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function show($id)
    {
        $transferRequest = TransferRequest::with([
            'fromLocation',
            'toLocation',
            'requester',
            'approver',
            'receiver',
            'transfers.product'
        ])->findOrFail($id);

        //$user = auth()->user();
        $user = Auth::user();

        if (!$user->isOwner() && 
            $transferRequest->from_location_id != $user->location_id && 
            $transferRequest->to_location_id != $user->location_id) {
            abort(403, 'Unauthorized access');
        }

        return view('transfers.show', compact('transferRequest'));
    }

    public function approve($id)
    {
        //$user = auth()->user();
        $user = Auth::user();

        if (!$user->canApproveTransfers()) {
            return redirect()->back()->with('error', 'Unauthorized action');
        }

        $transferRequest = TransferRequest::with('transfers')->findOrFail($id);

        if (!$transferRequest->isPending()) {
            return redirect()->back()->with('error', 'This request is not pending');
        }

        // Show approval page with editable quantities
        return view('transfers.approve', compact('transferRequest'));
    }

    public function processApproval(Request $request, $id)
    {
       // $user = auth()->user();
        $user = Auth::user();

        if (!$user->canApproveTransfers()) {
            return redirect()->back()->with('error', 'Unauthorized action');
        }

        $transferRequest = TransferRequest::with('transfers.product')->findOrFail($id);

        if (!$transferRequest->isPending()) {
            return redirect()->back()->with('error', 'This request is not pending');
        }

        $request->validate([
            'quantities' => 'required|array',
            'quantities.*' => 'required|integer|min:0',
        ]);

        DB::beginTransaction();
        try {
            foreach ($transferRequest->transfers as $transfer) {
                $approvedQty = $request->quantities[$transfer->id] ?? 0;

                if ($approvedQty == 0) {
                    // Cancel this item
                    $transfer->status = 'cancelled';
                    $transfer->save();
                    continue;
                }

                // Check inventory
                $inventory = Inventory::where('location_id', $transfer->from_location_id)
                    ->where('product_id', $transfer->product_id)
                    ->first();

                if (!$inventory || $inventory->boxes < $approvedQty) {
                    DB::rollBack();
                    return redirect()->back()
                        ->with('error', "Insufficient stock for {$transfer->product->name}");
                }

                // Update quantity and approve
                $transfer->quantity = $approvedQty;
                $transfer->status = 'in_transit';
                $transfer->approved_by = $user->id;
                $transfer->approved_at = now();
                $transfer->save();
            }

            // Update parent request
            $transferRequest->status = 'in_transit';
            $transferRequest->approved_by = $user->id;
            $transferRequest->approved_at = now();
            $transferRequest->save();

            ActivityLog::log('bulk_request_approved', [
                'table_name' => 'transfer_requests',
                'record_id' => $transferRequest->id,
                'details' => "Bulk request {$transferRequest->request_number} approved and marked in transit"
            ]);

            DB::commit();
            return redirect()->route('transfers.index')
                ->with('success', "Request {$transferRequest->request_number} approved and packed successfully");
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to approve request: ' . $e->getMessage());
        }
    }

    public function receive($id)
    {
        //$user = auth()->user();
        $user = Auth::user();
        $transferRequest = TransferRequest::with('transfers.product')->findOrFail($id);

        if ($transferRequest->to_location_id != $user->location_id) {
            return redirect()->back()->with('error', 'Unauthorized action');
        }

        if (!$transferRequest->isInTransit()) {
            return redirect()->back()->with('error', 'This request is not in transit');
        }

        DB::beginTransaction();
        try {
            foreach ($transferRequest->transfers as $transfer) {
                if ($transfer->status !== 'in_transit') {
                    continue;
                }

                // Update inventories
                $fromInventory = Inventory::where('location_id', $transfer->from_location_id)
                    ->where('product_id', $transfer->product_id)
                    ->lockForUpdate()
                    ->first();

                $toInventory = Inventory::firstOrCreate(
                    [
                        'location_id' => $transfer->to_location_id,
                        'product_id' => $transfer->product_id
                    ],
                    ['boxes' => 0, 'loose_units' => 0, 'damaged_units' => 0]
                );

                if ($fromInventory->boxes < $transfer->quantity) {
                    DB::rollBack();
                    return redirect()->back()->with('error', "Insufficient stock for {$transfer->product->name}");
                }

                $fromInventory->boxes -= $transfer->quantity;
                $fromInventory->save();

                $toInventory->boxes += $transfer->quantity;
                $toInventory->save();

                $transfer->status = 'completed';
                $transfer->received_by = $user->id;
                $transfer->received_at = now();
                $transfer->save();
            }

            // Update parent request
            $transferRequest->status = 'completed';
            $transferRequest->received_by = $user->id;
            $transferRequest->received_at = now();
            $transferRequest->save();

            ActivityLog::log('bulk_request_received', [
                'table_name' => 'transfer_requests',
                'record_id' => $transferRequest->id,
                'details' => "Bulk request {$transferRequest->request_number} received and completed"
            ]);

            DB::commit();
            return redirect()->route('transfers.index')
                ->with('success', "Request {$transferRequest->request_number} received successfully. Inventory updated.");
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to receive request: ' . $e->getMessage());
        }
    }

    public function cancel($id)
    {
      //  $user = auth()->user();
      $user = Auth::user();
        $transferRequest = TransferRequest::with('transfers')->findOrFail($id);

        if (!$user->isOwner() && $transferRequest->requested_by != $user->id && !$user->canApproveTransfers()) {
            return redirect()->back()->with('error', 'Unauthorized action');
        }

        if ($transferRequest->isCompleted()) {
            return redirect()->back()->with('error', 'Cannot cancel completed request');
        }

        DB::beginTransaction();
        try {
            foreach ($transferRequest->transfers as $transfer) {
                $transfer->status = 'cancelled';
                $transfer->save();
            }

            $transferRequest->status = 'cancelled';
            $transferRequest->save();

            ActivityLog::log('bulk_request_cancelled', [
                'table_name' => 'transfer_requests',
                'record_id' => $transferRequest->id,
                'details' => "Bulk request {$transferRequest->request_number} cancelled"
            ]);

            DB::commit();
            return redirect()->back()->with('success', 'Request cancelled successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to cancel request: ' . $e->getMessage());
        }
    }
}