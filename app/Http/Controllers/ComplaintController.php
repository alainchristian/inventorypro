<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use App\Models\Product;
use App\Models\Inventory;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ComplaintController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        
        $query = Complaint::with(['location', 'product', 'reporter', 'resolver'])
            ->orderBy('reported_at', 'desc');

        // Filter by location if not owner
        if (!$user->isOwner()) {
            $query->where('location_id', $user->location_id);
        }

        // Status filter
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        $complaints = $query->paginate(15);

        return view('complaints.index', compact('complaints'));
    }

    public function create()
    {
        $user = Auth::user();
        // Get inventory at user's location
        $inventory = Inventory::with('product')
            ->where('location_id', $user->location_id)
            ->where('boxes', '>', 0) // Need at least one box to open for replacement
            ->get();

        return view('complaints.create', compact('inventory'));
    }

    public function store(Request $request)
    {
       // $user = auth()->user();
        $user = Auth::user();

        $request->validate([
            'product_id' => 'required|exists:products,id',
            'description' => 'required|string|max:1000',
            'damaged_units' => 'required|integer|min:1',
        ]);

        // Check if there's a box to open for replacement
        $inventory = Inventory::where('location_id', $user->location_id)
            ->where('product_id', $request->product_id)
            ->first();

        if (!$inventory || $inventory->boxes < 1) {
            return redirect()->back()->with('error', 'No boxes available to open for replacement');
        }

        DB::beginTransaction();
        try {
            $product = Product::find($request->product_id);
            
            // Create complaint
            $complaint = Complaint::create([
                'location_id' => $user->location_id,
                'product_id' => $request->product_id,
                'description' => $request->description,
                'status' => 'pending',
                'reported_by' => $user->id,
                'reported_at' => now(),
                'damaged_units' => $request->damaged_units,
            ]);

            // Open box and handle replacement
            $inventory->openBox(); // Reduces boxes by 1, adds units_per_box to loose_units
            
            // Remove replacement units from loose units
            $inventory->removeLooseUnits($request->damaged_units);
            
            // Add to damaged units
            $inventory->addDamagedUnits($request->damaged_units);

            // Update complaint
            $complaint->update([
                'status' => 'resolved',
                'resolved_by' => $user->id,
                'resolved_at' => now(),
                'replacement_given' => true,
                'box_opened' => 1,
                'resolution_notes' => "Box opened automatically. {$request->damaged_units} unit(s) replaced with good units from opened box."
            ]);

            ActivityLog::log('complaint_filed_and_resolved', [
                'table_name' => 'complaints',
                'record_id' => $complaint->id,
                'details' => "Complaint filed and resolved: Opened 1 box of {$request->product_id}, replaced {$request->damaged_units} damaged unit(s)"
            ]);

            DB::commit();
            return redirect()->route('complaints.index')
                ->with('success', "Complaint filed and resolved. Box opened, {$request->damaged_units} damaged unit(s) replaced.");
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to process complaint: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $complaint = Complaint::with(['location', 'product', 'reporter', 'resolver'])->findOrFail($id);
      //  $user = auth()->user();
        $user = Auth::user();

        // Check permission
        if (!$user->isOwner() && $complaint->location_id != $user->location_id) {
            abort(403, 'Unauthorized access');
        }

        return view('complaints.show', compact('complaint'));
    }

    public function notifySupplier($id)
    {
       // $user = auth()->user();
        $user = Auth::user();

        if (!$user->isOwner()) {
            return redirect()->back()->with('error', 'Only owner can notify supplier');
        }

        $complaint = Complaint::findOrFail($id);

        DB::beginTransaction();
        try {
            $complaint->notifySupplier("Supplier notified by {$user->name}");

            ActivityLog::log('supplier_notified', [
                'table_name' => 'complaints',
                'record_id' => $complaint->id,
                'details' => "Supplier notified about complaint #{$complaint->id}"
            ]);

            DB::commit();
            return redirect()->back()->with('success', 'Supplier notification logged');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to notify supplier: ' . $e->getMessage());
        }
    }
}