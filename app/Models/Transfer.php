<?php

// FILE: app/Models/Transfer.php (ADD THIS RELATIONSHIP)

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transfer extends Model
{
    use HasFactory;

    protected $fillable = [
        
        'transfer_request_id', // ADD THIS
        'from_location_id',
        'to_location_id',
        'product_id',
        'quantity',
        'status',
        'requested_by',
        'requested_at',
        'approved_by',
        'approved_at',
        'received_by',
        'received_at',
        'notes'
    ];

    protected $casts = [
        'quantity' => 'integer',
        'requested_at' => 'datetime',
        'approved_at' => 'datetime',
        'received_at' => 'datetime',
    ];

    // ADD THIS RELATIONSHIP
    public function transferRequest()
    {
        return $this->belongsTo(TransferRequest::class, 'transfer_request_id');
    }

    // Existing relationships
    public function fromLocation()
    {
        return $this->belongsTo(Location::class, 'from_location_id');
    }

    public function toLocation()
    {
        return $this->belongsTo(Location::class, 'to_location_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function requester()
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'received_by');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeInTransit($query)
    {
        return $query->where('status', 'in_transit');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }

    // Status Checks
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function isInTransit(): bool
    {
        return $this->status === 'in_transit';
    }

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    public function isCancelled(): bool
    {
        return $this->status === 'cancelled';
    }

    // State Transitions
    public function approve(User $user): bool
    {
        if (!$this->isPending()) {
            return false;
        }

        $this->status = 'approved';
        $this->approved_by = $user->id;
        $this->approved_at = now();
        return $this->save();
    }

    public function markInTransit(): bool
    {
        if (!$this->isApproved()) {
            return false;
        }

        $this->status = 'in_transit';
        return $this->save();
    }

    public function receive(User $user): bool
    {
        if (!$this->isInTransit()) {
            return false;
        }

        $this->status = 'completed';
        $this->received_by = $user->id;
        $this->received_at = now();
        return $this->save();
    }

    public function cancel(): bool
    {
        if ($this->isCompleted()) {
            return false;
        }

        $this->status = 'cancelled';
        return $this->save();
    }

    // Helper Methods
    public function getStatusBadgeColor(): string
    {
        return match($this->status) {
            'pending' => 'yellow',
            'approved' => 'blue',
            'in_transit' => 'purple',
            'completed' => 'green',
            'cancelled' => 'red',
            default => 'gray'
        };
    }

    public function canBeApproved(): bool
    {
        if (!$this->isPending()) {
            return false;
        }

        $inventory = Inventory::where('location_id', $this->from_location_id)
            ->where('product_id', $this->product_id)
            ->first();

        return $inventory && $inventory->boxes >= $this->quantity;
    }
}