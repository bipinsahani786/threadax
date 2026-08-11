<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'order_id',
        'transaction_id',
        'razorpay_payment_id',
        'amount',
        'status',
        'payment_method',
        'refund_id',
        'refund_status',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function isRefundable(): bool
    {
        return $this->status === 'success'
            && $this->payment_method === 'razorpay'
            && $this->refund_status === 'none'
            && $this->razorpay_payment_id !== null;
    }
}
