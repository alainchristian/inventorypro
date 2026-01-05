<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = [
        'location_id',
        'product_id',
        'quantity',
        'unit_price',
        'total_amount',
        'sold_by',
        'sold_at',
        'customer_name',
        'customer_phone',
        'receipt_number',
        'notes'
    ];

    protected $casts = [
        'quantity' => 'integer',
        'unit_price' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'sold_at' => 'datetime',
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

    public function seller()
    {
        return $this->belongsTo(User::class, 'sold_by');
    }

    // Scopes
    public function scopeToday($query)
    {
        return $query->whereDate('sold_at', today());
    }

    public function scopeThisWeek($query)
    {
        return $query->whereBetween('sold_at', [now()->startOfWeek(), now()->endOfWeek()]);
    }

    public function scopeThisMonth($query)
    {
        return $query->whereMonth('sold_at', now()->month)
                    ->whereYear('sold_at', now()->year);
    }

    public function scopeByLocation($query, $locationId)
    {
        return $query->where('location_id', $locationId);
    }

    public function scopeByProduct($query, $productId)
    {
        return $query->where('product_id', $productId);
    }

    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('sold_at', [$startDate, $endDate]);
    }

    // Accessors
    public function getFormattedReceiptNumberAttribute()
    {
        return 'RCP-' . str_pad($this->receipt_number, 8, '0', STR_PAD_LEFT);
    }

    // Helper Methods
    public static function generateReceiptNumber(): string
    {
        $lastSale = self::orderBy('id', 'desc')->first();
        $number = $lastSale ? ((int)$lastSale->receipt_number + 1) : 1;
        return str_pad($number, 8, '0', STR_PAD_LEFT);
    }

    public function calculateProfit(): float
    {
        $purchasePrice = $this->product->purchase_price ?? 0;
        return ($this->unit_price - $purchasePrice) * $this->quantity;
    }
}
