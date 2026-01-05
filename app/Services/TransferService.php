<?php

// FILE: app/Services/TransferService.php

namespace App\Services;

use App\Models\Transfer;
use App\Models\Inventory;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\DB;

class TransferService
{
    public function createTransferRequest($fromLocationId, $toLocationId, $productId, $quantity, $userId, $notes = null)
    {
        return Transfer::create([
            'from_location_id' => $fromLocationId,
            'to_location_id' => $toLocationId,
            'product_id' => $productId,
            'quantity' => $quantity,
            'status' => 'pending',
            'requested_by' => $userId,
            'requested_at' => now(),
            'notes' => $notes
        ]);
    }

    public function approveTransfer($transferId, $userId)
    {
        DB::beginTransaction();
        try {
            $transfer = Transfer::findOrFail($transferId);

            if (!$transfer->canBeApproved()) {
                throw new \Exception('Transfer cannot be approved: insufficient stock or invalid status');
            }

            $transfer->approve($userId);
            $transfer->markInTransit();

            ActivityLog::log('transfer_approved', [
                'table_name' => 'transfers',
                'record_id' => $transfer->id,
                'details' => "Transfer approved and marked in transit"
            ]);

            DB::commit();
            return $transfer;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function completeTransfer($transferId, $userId)
    {
        DB::beginTransaction();
        try {
            $transfer = Transfer::with('product')->findOrFail($transferId);

            if (!$transfer->isInTransit()) {
                throw new \Exception('Transfer is not in transit');
            }

            // Update inventories
            $fromInventory = Inventory::firstOrCreate(
                ['location_id' => $transfer->from_location_id, 'product_id' => $transfer->product_id],
                ['boxes' => 0, 'loose_units' => 0]
            );

            $toInventory = Inventory::firstOrCreate(
                ['location_id' => $transfer->to_location_id, 'product_id' => $transfer->product_id],
                ['boxes' => 0, 'loose_units' => 0]
            );

            if (!$fromInventory->removeBoxes($transfer->quantity)) {
                throw new \Exception('Insufficient stock in source location');
            }

            $toInventory->addBoxes($transfer->quantity);

            // Complete transfer
            $transfer->receive($userId);

            ActivityLog::log('transfer_completed', [
                'table_name' => 'transfers',
                'record_id' => $transfer->id,
                'details' => "Transfer completed: {$transfer->quantity} boxes transferred"
            ]);

            DB::commit();
            return $transfer;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}