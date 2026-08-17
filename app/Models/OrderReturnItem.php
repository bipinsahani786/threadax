<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderReturnItem extends Model
{
    protected $fillable = [
        'order_return_id',
        'order_item_id',
        'product_id',
        'product_variant_id',
        'exchange_variant_id',
        'quantity',
        'price',
        'total',
        'reason',
        'condition',
        'is_restocked',
    ];

    protected $casts = [
        'price'        => 'decimal:2',
        'total'        => 'decimal:2',
        'is_restocked' => 'boolean',
    ];

    public function returnRequest()
    {
        return $this->belongsTo(OrderReturn::class, 'order_return_id');
    }

    public function orderItem()
    {
        return $this->belongsTo(OrderItem::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function originalVariant()
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }

    public function exchangeVariant()
    {
        return $this->belongsTo(ProductVariant::class, 'exchange_variant_id');
    }
}
