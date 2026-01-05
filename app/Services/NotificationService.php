<?php

// FILE: app/Services/NotificationService.php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Mail;

class NotificationService
{
    public function notifyTransferRequest($transfer)
    {
        // Get warehouse managers
        $managers = User::where('role', 'warehouse_manager')
            ->where('is_active', true)
            ->get();

        // In production, send email/SMS
        // For now, just log it
        foreach ($managers as $manager) {
            // Mail::to($manager->email)->send(new TransferRequestMail($transfer));
        }
    }

    public function notifyTransferApproved($transfer)
    {
        // Notify the requester
        if ($transfer->requester) {
            // Mail::to($transfer->requester->email)->send(new TransferApprovedMail($transfer));
        }
    }

    public function notifyLowStock($inventory)
    {
        // Notify relevant managers about low stock
        $managers = User::whereIn('role', ['owner', 'warehouse_manager', 'shop_manager'])
            ->where('is_active', true)
            ->get();

        foreach ($managers as $manager) {
            // Mail::to($manager->email)->send(new LowStockAlert($inventory));
        }
    }
}