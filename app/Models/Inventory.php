<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use HasFactory;

    protected $table = 'inventory';

    protected $fillable = [
        'location_id',
        'product_id',
        'boxes',
        'loose_units',
        'damaged_units',
        'last_updated'
    ];

    protected $casts = [
        'boxes' => 'integer',
        'loose_units' => 'integer',
        'damaged_units' => 'integer',
        'last_updated' => 'datetime',
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

    // Accessors
    public function getTotalUnitsAttribute()
    {
        return ($this->boxes * $this->product->units_per_box) + $this->loose_units;
    }

    public function getIsLowStockAttribute()
    {
        return $this->boxes < $this->product->min_stock_level;
    }

    public function getStockValueAttribute()
    {
        return $this->boxes * $this->product->selling_price;
    }

    // Helper Methods
    public function addBoxes(int $quantity): bool
    {
        $this->boxes += $quantity;
        return $this->save();
    }

    public function removeBoxes(int $quantity): bool
    {
        if ($this->boxes < $quantity) {
            return false;
        }
        $this->boxes -= $quantity;
        return $this->save();
    }

    public function openBox(): bool
    {
        if ($this->boxes < 1) {
            return false;
        }
        
        $this->boxes -= 1;
        $this->loose_units += $this->product->units_per_box;
        return $this->save();
    }

    public function removeLooseUnits(int $quantity): bool
    {
        if ($this->loose_units < $quantity) {
            return false;
        }
        $this->loose_units -= $quantity;
        return $this->save();
    }

    public function addDamagedUnits(int $quantity): bool
    {
        $this->damaged_units += $quantity;
        return $this->save();
    }
}
