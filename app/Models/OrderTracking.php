<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderTracking extends Model
{
    use HasFactory;

    protected $table = 'order_trackings';

    protected $fillable = [
        'order_id',
        'status',
        'title',
        'location',
        'activity',
        'courier_name',
        'tracking_number',
        'event_time',
    ];

    protected $casts = [
        'event_time' => 'datetime',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get badge color styling according to status
     */
    public function getStatusBadgeAttribute(): array
    {
        return match ($this->status) {
            'order_placed'     => ['bg' => 'bg-slate-100', 'text' => 'text-slate-700', 'icon' => '📦', 'label' => 'Order Placed'],
            'processing'       => ['bg' => 'bg-blue-50', 'text' => 'text-blue-700', 'icon' => '⚙️', 'label' => 'Processing'],
            'packed'           => ['bg' => 'bg-purple-50', 'text' => 'text-purple-700', 'icon' => '🎁', 'label' => 'Packed at Warehouse'],
            'shipped'          => ['bg' => 'bg-indigo-50', 'text' => 'text-indigo-700', 'icon' => '🚚', 'label' => 'Shipped / Dispatched'],
            'in_transit'       => ['bg' => 'bg-cyan-50', 'text' => 'text-cyan-700', 'icon' => '✈️', 'label' => 'In Transit'],
            'out_for_delivery' => ['bg' => 'bg-amber-50', 'text' => 'text-amber-700', 'icon' => '🛵', 'label' => 'Out for Delivery'],
            'delivered'        => ['bg' => 'bg-emerald-50', 'text' => 'text-emerald-700', 'icon' => '🟢', 'label' => 'Delivered'],
            'cancelled'        => ['bg' => 'bg-rose-50', 'text' => 'text-rose-700', 'icon' => '🔴', 'label' => 'Cancelled'],
            'rto'              => ['bg' => 'bg-orange-50', 'text' => 'text-orange-700', 'icon' => '↩️', 'label' => 'Return to Origin'],
            default            => ['bg' => 'bg-slate-100', 'text' => 'text-slate-700', 'icon' => '📍', 'label' => ucfirst(str_replace('_', ' ', $this->status))],
        };
    }
}
