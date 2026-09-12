<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductVariant extends Model
{
    protected $fillable = [
        'product_id',
        'size',
        'color',
        'color_hex',
        'sku',
        'price',
        'stock',
        'low_stock_threshold',
        'is_active',
    ];

    protected $casts = [
        'price'               => 'decimal:2',
        'stock'               => 'integer',
        'low_stock_threshold' => 'integer',
        'is_active'           => 'boolean',
    ];

    // ─── Relationships ────────────────────────────────────────

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    // ─── Accessors ────────────────────────────────────────────

    /**
     * Check if variant is out of stock.
     */
    public function getIsOutOfStockAttribute(): bool
    {
        return $this->stock <= 0;
    }

    /**
     * Check if variant is low on stock.
     */
    public function getIsLowStockAttribute(): bool
    {
        return $this->stock > 0 && $this->stock <= $this->low_stock_threshold;
    }

    /**
     * Get the effective price (variant price or falls back to product price).
     */
    public function getEffectivePriceAttribute(): float
    {
        return (float) ($this->price ?? $this->product?->price ?? 0);
    }

    // ─── Scopes ───────────────────────────────────────────────

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeInStock($query)
    {
        return $query->where('stock', '>', 0);
    }
}
