<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class OrderReturn extends Model
{
    protected $fillable = [
        'order_id',
        'user_id',
        'return_number',
        'type',
        'status',
        'reason',
        'customer_notes',
        'refund_mode',
        'refund_amount',
        'upi_id',
        'bank_name',
        'bank_account_number',
        'bank_ifsc',
        'bank_beneficiary_name',
        'refund_transaction_id',
        'pickup_courier',
        'pickup_awb',
        'pickup_scheduled_date',
        'admin_notes',
        'rejection_reason',
        'approved_at',
        'picked_up_at',
        'received_at',
        'refunded_at',
        'completed_at',
        'rejected_at',
    ];

    protected $casts = [
        'refund_amount'         => 'decimal:2',
        'pickup_scheduled_date' => 'date',
        'approved_at'           => 'datetime',
        'picked_up_at'          => 'datetime',
        'received_at'           => 'datetime',
        'refunded_at'           => 'datetime',
        'completed_at'          => 'datetime',
        'rejected_at'           => 'datetime',
    ];

    /**
     * Generate Unique Return Number
     */
    public static function generateReturnNumber(): string
    {
        do {
            $number = 'RET-TX-' . strtoupper(Str::random(6));
        } while (self::where('return_number', $number)->exists());

        return $number;
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderReturnItem::class);
    }

    public function images()
    {
        return $this->hasMany(OrderReturnImage::class);
    }

    public function isExchange(): bool
    {
        return $this->type === 'exchange';
    }

    public function isRefund(): bool
    {
        return $this->type === 'return';
    }

    public function isPending(): bool
    {
        return $this->status === 'requested';
    }

    public function isApproved(): bool
    {
        return in_array($this->status, ['approved', 'pickup_scheduled', 'picked_up', 'received_at_hub']);
    }

    public function isCompleted(): bool
    {
        return in_array($this->status, ['refunded', 'exchanged', 'completed']);
    }

    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    public function getStatusBadgeAttribute(): array
    {
        return match ($this->status) {
            'requested' => [
                'bg' => 'bg-amber-50', 'text' => 'text-amber-700', 'border' => 'border-amber-200',
                'dot' => 'bg-amber-500', 'label' => 'Review Pending', 'icon' => '⏳'
            ],
            'approved' => [
                'bg' => 'bg-sky-50', 'text' => 'text-sky-700', 'border' => 'border-sky-200',
                'dot' => 'bg-sky-500', 'label' => 'Approved', 'icon' => '✅'
            ],
            'pickup_scheduled' => [
                'bg' => 'bg-indigo-50', 'text' => 'text-indigo-700', 'border' => 'border-indigo-200',
                'dot' => 'bg-indigo-500', 'label' => 'Pickup Scheduled', 'icon' => '🚚'
            ],
            'picked_up' => [
                'bg' => 'bg-purple-50', 'text' => 'text-purple-700', 'border' => 'border-purple-200',
                'dot' => 'bg-purple-500', 'label' => 'Parcel In Transit', 'icon' => '📦'
            ],
            'received_at_hub' => [
                'bg' => 'bg-teal-50', 'text' => 'text-teal-700', 'border' => 'border-teal-200',
                'dot' => 'bg-teal-500', 'label' => 'Received & QC Passed', 'icon' => '🔍'
            ],
            'refunded' => [
                'bg' => 'bg-emerald-50', 'text' => 'text-emerald-700', 'border' => 'border-emerald-200',
                'dot' => 'bg-emerald-500', 'label' => 'Refund Completed', 'icon' => '💵'
            ],
            'exchanged' => [
                'bg' => 'bg-emerald-50', 'text' => 'text-emerald-700', 'border' => 'border-emerald-200',
                'dot' => 'bg-emerald-500', 'label' => 'Exchange Dispatched', 'icon' => '🔄'
            ],
            'completed' => [
                'bg' => 'bg-emerald-50', 'text' => 'text-emerald-700', 'border' => 'border-emerald-200',
                'dot' => 'bg-emerald-500', 'label' => 'Completed', 'icon' => '✨'
            ],
            'rejected' => [
                'bg' => 'bg-rose-50', 'text' => 'text-rose-700', 'border' => 'border-rose-200',
                'dot' => 'bg-rose-500', 'label' => 'Request Rejected', 'icon' => '❌'
            ],
            'cancelled' => [
                'bg' => 'bg-slate-100', 'text' => 'text-slate-700', 'border' => 'border-slate-200',
                'dot' => 'bg-slate-500', 'label' => 'Cancelled by User', 'icon' => '🚫'
            ],
            default => [
                'bg' => 'bg-slate-50', 'text' => 'text-slate-700', 'border' => 'border-slate-200',
                'dot' => 'bg-slate-500', 'label' => ucfirst($this->status), 'icon' => '📋'
            ],
        };
    }

    public function getReasonLabelAttribute(): string
    {
        $reasons = [
            'size_too_small'        => 'Size is too small / tight fit',
            'size_too_large'        => 'Size is too large / loose fit',
            'defective_product'     => 'Damaged / Defective item received',
            'wrong_item_received'   => 'Wrong item / color received',
            'quality_not_expected'  => 'Fabric / Quality not as expected',
            'color_mismatch'        => 'Color looks different from website',
            'late_delivery'         => 'Item arrived later than expected',
            'changed_mind'          => 'Changed mind / No longer needed',
            'other'                 => 'Other / Custom reason',
        ];

        return $reasons[$this->reason] ?? ucfirst(str_replace('_', ' ', $this->reason));
    }
}
