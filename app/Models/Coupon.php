<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Coupon extends Model
{
    protected $fillable = [
        'code',
        'type',
        'value',
        'min_order_amount',
        'max_discount_amount',
        'max_uses',
        'max_uses_per_user',
        'used_count',
        'starts_at',
        'expires_at',
        'is_active',
        'description',
    ];

    protected $casts = [
        'value'              => 'decimal:2',
        'min_order_amount'   => 'decimal:2',
        'max_discount_amount'=> 'decimal:2',
        'is_active'          => 'boolean',
        'starts_at'          => 'datetime',
        'expires_at'         => 'datetime',
    ];

    // ─── Relationships ────────────────────────────────────────

    public function usages(): HasMany
    {
        return $this->hasMany(CouponUsage::class);
    }

    // ─── Helpers ──────────────────────────────────────────────

    public function isValid(): bool
    {
        if (! $this->is_active) return false;
        if ($this->expires_at && $this->expires_at->isPast()) return false;
        if ($this->starts_at && $this->starts_at->isFuture()) return false;
        if ($this->max_uses && $this->used_count >= $this->max_uses) return false;
        return true;
    }

    public function calculateDiscount(float $subtotal): float
    {
        if ($this->type === 'percent') {
            $discount = ($subtotal * $this->value) / 100;
            if ($this->max_discount_amount) {
                $discount = min($discount, (float) $this->max_discount_amount);
            }
        } else {
            $discount = (float) $this->value;
        }

        return min($discount, $subtotal);
    }
}
