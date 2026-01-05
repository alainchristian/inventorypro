<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Location extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'type',
        'manager_name',
        'address',
        'phone',
        'email',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Relationships
    public function inventory()
    {
        return $this->hasMany(Inventory::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }

    public function transfersFrom()
    {
        return $this->hasMany(Transfer::class, 'from_location_id');
    }

    public function transfersTo()
    {
        return $this->hasMany(Transfer::class, 'to_location_id');
    }

    public function complaints()
    {
        return $this->hasMany(Complaint::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeWarehouses($query)
    {
        return $query->where('type', 'warehouse');
    }

    public function scopeShops($query)
    {
        return $query->where('type', 'shop');
    }

    // Accessors
    public function getIsWarehouseAttribute()
    {
        return $this->type === 'warehouse';
    }

    public function getIsShopAttribute()
    {
        return $this->type === 'shop';
    }

    // Helper Methods
    public function getTotalStock()
    {
        return $this->inventory()->sum('boxes');
    }

    public function getLowStockProducts()
    {
        return $this->inventory()
            ->with('product')
            ->get()
            ->filter(function ($inv) {
                return $inv->boxes < $inv->product->min_stock_level;
            });
    }
}

