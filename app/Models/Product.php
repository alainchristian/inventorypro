<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'name',
        'description',
        'units_per_box',
        'purchase_price',
        'selling_price',
        'min_stock_level',
        'supplier_name',
        'supplier_phone',
        'barcode',
        'is_active'
    ];

    protected $casts = [
        'units_per_box' => 'integer',
        'purchase_price' => 'decimal:2',
        'selling_price' => 'decimal:2',
        'min_stock_level' => 'integer',
        'is_active' => 'boolean',
    ];

    // Relationships
    public function inventory()
    {
        return $this->hasMany(Inventory::class, 'product_id', 'id');
    }

    public function transfers()
    {
        return $this->hasMany(Transfer::class, 'product_id', 'id');
    }

    public function sales()
    {
        return $this->hasMany(Sale::class, 'product_id', 'id');
    }

    public function complaints()
    {
        return $this->hasMany(Complaint::class, 'product_id', 'id');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeLowStock($query)
    {
        return $query->whereHas('inventory', function ($q) {
            $q->whereRaw('boxes < min_stock_level');
        });
    }

    // Accessors
    public function getProfitMarginAttribute()
    {
        if (!$this->purchase_price || $this->purchase_price == 0) {
            return 0;
        }
        return (($this->selling_price - $this->purchase_price) / $this->purchase_price) * 100;
    }

    public function getProfitPerBoxAttribute()
    {
        return $this->selling_price - $this->purchase_price;
    }

    // Helper Methods
    public function getTotalStockAllLocations()
    {
        return $this->inventory()->sum('boxes');
    }

    public function getStockAtLocation($locationId)
    {
        return $this->inventory()->where('location_id', $locationId)->first();
    }

    public function isLowStockAtLocation($locationId)
    {
        $stock = $this->getStockAtLocation($locationId);
        return $stock && $stock->boxes < $this->min_stock_level;
    }
}
