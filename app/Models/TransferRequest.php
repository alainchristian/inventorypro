<?php

// FILE: app/Models/TransferRequest.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransferRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'request_number',
        'from_location_id',
        'to_location_id',
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
        'requested_at' => 'datetime',
        'approved_at' => 'datetime',
        'received_at' => 'datetime',
    ];

    // Relationships
    public function transfers()
    {
        return $this->hasMany(Transfer::class, 'transfer_request_id');
    }

    public function fromLocation()
    {
        return $this->belongsTo(Location::class, 'from_location_id');
    }

    public function toLocation()
    {
        return $this->belongsTo(Location::class, 'to_location_id');
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

    public function scopeInTransit($query)
    {
        return $query->where('status', 'in_transit');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    // Status Checks
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isInTransit(): bool
    {
        return $this->status === 'in_transit';
    }

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    // Helper Methods
    public function getTotalBoxes()
    {
        return $this->transfers()->sum('quantity');
    }

    public function getTotalProducts()
    {
        return $this->transfers()->count();
    }

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

    // Generate unique request number
    public static function generateRequestNumber(): string
    {
        $year = date('Y');
        $lastRequest = self::whereYear('created_at', $year)
            ->orderBy('id', 'desc')
            ->first();
        
        $number = $lastRequest ? ((int) substr($lastRequest->request_number, -5)) + 1 : 1;
        
        return 'REQ-' . $year . '-' . str_pad($number, 5, '0', STR_PAD_LEFT);
    }
}