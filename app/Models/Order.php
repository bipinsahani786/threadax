<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id', 'order_number', 'address_id', 'coupon_id', 'coupon_code',
        'subtotal', 'discount', 'shipping', 'tax', 'total', 
        'status', 'payment_method', 'payment_status'
    ];

    protected $casts = [
        'subtotal'  => 'decimal:2',
        'discount'  => 'decimal:2',
        'shipping'  => 'decimal:2',
        'tax'       => 'decimal:2',
        'total'     => 'decimal:2',
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
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
}
