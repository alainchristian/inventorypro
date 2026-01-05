<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use  HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'location_id',
        'is_active',
        'phone',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_active' => 'boolean',
    ];

    // Relationships
    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class);
    }

    public function sales()
    {
        return $this->hasMany(Sale::class, 'sold_by');
    }

    public function transfersRequested()
    {
        return $this->hasMany(Transfer::class, 'requested_by');
    }

    public function transfersApproved()
    {
        return $this->hasMany(Transfer::class, 'approved_by');
    }

    public function transfersReceived()
    {
        return $this->hasMany(Transfer::class, 'received_by');
    }

    public function complaintsReported()
    {
        return $this->hasMany(Complaint::class, 'reported_by');
    }

    public function complaintsResolved()
    {
        return $this->hasMany(Complaint::class, 'resolved_by');
    }

    // Role Checks
    public function isOwner(): bool
    {
        return $this->role === 'owner';
    }

    public function isWarehouseManager(): bool
    {
        return $this->role === 'warehouse_manager';
    }

    public function isShopManager(): bool
    {
        return $this->role === 'shop_manager';
    }

    public function isSalesperson(): bool
    {
        return $this->role === 'salesperson';
    }

    public function canViewPricing(): bool
    {
        return $this->isOwner();
    }

    public function canApproveTransfers(): bool
    {
        return $this->isOwner() || $this->isWarehouseManager();
    }

    public function canRequestTransfers(): bool
    {
        return $this->isShopManager() || $this->isSalesperson();
    }

    public function canMakeSales(): bool
    {
        return !$this->isWarehouseManager();
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByRole($query, $role)
    {
        return $query->where('role', $role);
    }

    public function scopeAtLocation($query, $locationId)
    {
        return $query->where('location_id', $locationId);
    }
}