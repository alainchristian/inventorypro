<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Complaint extends Model
{
    use HasFactory;

    protected $fillable = [
        'location_id',
        'product_id',
        'description',
        'status',
        'reported_by',
        'reported_at',
        'resolved_by',
        'resolved_at',
        'replacement_given',
        'damaged_units',
        'box_opened',
        'resolution_notes'
    ];

    protected $casts = [
        'reported_at' => 'datetime',
        'resolved_at' => 'datetime',
        'replacement_given' => 'boolean',
        'damaged_units' => 'integer',
        'box_opened' => 'integer',
    ];

    // Relationships
    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function reporter()
    {
        return $this->belongsTo(User::class, 'reported_by');
    }

    public function resolver()
    {
        return $this->belongsTo(User::class, 'resolved_by');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeResolved($query)
    {
        return $query->where('status', 'resolved');
    }

    public function scopeSupplierNotified($query)
    {
        return $query->where('status', 'supplier_notified');
    }

    // Status Checks
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isResolved(): bool
    {
        return $this->status === 'resolved';
    }

    // State Transitions
    public function resolve(User $user, string $notes = null): bool
    {
        $this->status = 'resolved';
        $this->resolved_by = $user->id;
        $this->resolved_at = now();
        $this->replacement_given = true;
        
        if ($notes) {
            $this->resolution_notes = $notes;
        }

        return $this->save();
    }

    public function notifySupplier(string $notes = null): bool
    {
        $this->status = 'supplier_notified';
        
        if ($notes) {
            $this->resolution_notes = $notes;
        }

        return $this->save();
    }

    // Helper Methods
    public function getStatusBadgeColor(): string
    {
        return match($this->status) {
            'pending' => 'yellow',
            'resolved' => 'green',
            'supplier_notified' => 'blue',
            'closed' => 'gray',
            default => 'gray'
        };
    }
}
