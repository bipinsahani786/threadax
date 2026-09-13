<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id', 'order_number', 'address_id', 'coupon_id', 'coupon_code',
        'subtotal', 'discount', 'shipping', 'tax', 'total', 
        'status', 'payment_method', 'payment_status',
        'courier_name', 'tracking_number', 'tracking_url',
        'shiprocket_order_id', 'shiprocket_shipment_id', 'shiprocket_awb_code', 'shiprocket_courier_name',
        'estimated_delivery_date', 'shipped_at', 'delivered_at'
    ];

    protected $casts = [
        'subtotal'                => 'decimal:2',
        'discount'                => 'decimal:2',
        'shipping'                => 'decimal:2',
        'tax'                     => 'decimal:2',
        'total'                   => 'decimal:2',
        'estimated_delivery_date' => 'date',
        'shipped_at'              => 'datetime',
        'delivered_at'            => 'datetime',
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function trackings()
    {
        return $this->hasMany(OrderTracking::class, 'order_id')->orderBy('event_time', 'desc');
    }

    public function latestTracking()
    {
        return $this->hasOne(OrderTracking::class, 'order_id')->latestOfMany('event_time');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function address()
    {
        return $this->belongsTo(Address::class);
    }

    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function isDelivered(): bool
    {
        return $this->status === 'delivered';
    }

    /**
     * Scope to only include confirmed/placed orders (excludes abandoned/incomplete online checkouts).
     */
    public function scopePlaced($query)
    {
        return $query->where(function ($q) {
            $q->where('payment_status', 'paid')
              ->orWhere('payment_method', 'cod');
        });
    }

    public function getEffectiveCourierAttribute(): ?string
    {
        return $this->shiprocket_courier_name ?: $this->courier_name;
    }

    public function getEffectiveAwbAttribute(): ?string
    {
        return $this->shiprocket_awb_code ?: $this->tracking_number;
    }

    public function getEffectiveTrackingUrlAttribute(): ?string
    {
        if ($this->tracking_url) {
            return $this->tracking_url;
        }

        $awb = $this->effective_awb;
        if (!$awb) {
            return null;
        }

        $courier = strtolower($this->effective_courier ?? '');
        if (str_contains($courier, 'delhivery')) {
            return "https://www.delhivery.com/track/package/{$awb}";
        } elseif (str_contains($courier, 'bluedart') || str_contains($courier, 'blue dart')) {
            return "https://www.bluedart.com/tracking?awb={$awb}";
        } elseif (str_contains($courier, 'dtdc')) {
            return "https://www.dtdc.in/tracking/shipment-tracking.asp?trkType=awb&strCnno={$awb}";
        } elseif (str_contains($courier, 'ekart')) {
            return "https://ekartlogistics.com/shipmenttrack/{$awb}";
        } elseif (str_contains($courier, 'shadowfax')) {
            return "https://tracker.shadowfax.in/#/track/{$awb}";
        } elseif ($this->shiprocket_shipment_id || $this->shiprocket_order_id) {
            return "https://shiprocket.co/tracking/{$awb}";
        }

        return "https://shiprocket.co/tracking/{$awb}";
    }

    public function transactions()
    {
        return $this->hasMany(PaymentTransaction::class)->latest();
    }

    public function returns()
    {
        return $this->hasMany(OrderReturn::class)->latest();
    }

    public function latestReturn()
    {
        return $this->hasOne(OrderReturn::class)->latestOfMany();
    }

    public function activeReturn()
    {
        return $this->hasOne(OrderReturn::class)->whereNotIn('status', ['cancelled', 'rejected'])->latestOfMany();
    }

    /**
     * Check if the order is eligible for Return / Size Exchange under the 7-day policy.
     */
    public function isEligibleForReturn(): bool
    {
        if ($this->status !== 'delivered') {
            return false;
        }

        // If an active return is already in progress or completed, cannot submit another
        if ($this->activeReturn()->exists()) {
            return false;
        }

        // Check 7-day return window from delivered_at (or fallback to updated_at)
        $deliveryDate = $this->delivered_at ?: $this->updated_at;
        if (!$deliveryDate) {
            return false;
        }

        return $deliveryDate->diffInDays(now()) <= 7;
    }

    /**
     * Get remaining days in return window.
     */
    public function getReturnDaysLeftAttribute(): int
    {
        $deliveryDate = $this->delivered_at ?: $this->updated_at;
        if (!$deliveryDate) return 0;
        
        $daysPassed = $deliveryDate->diffInDays(now());
        return max(0, 7 - (int)$daysPassed);
    }
}

