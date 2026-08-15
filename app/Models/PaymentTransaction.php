<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Request;

class PaymentTransaction extends Model
{
    protected $fillable = [
        'order_id',
        'user_id',
        'payment_id',
        'transaction_id',
        'gateway',
        'event',
        'amount',
        'currency',
        'status',
        'method',
        'error_code',
        'error_description',
        'payload',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'amount'  => 'decimal:2',
        'payload' => 'array',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class);
    }

    /**
     * Convenient static logger helper
     */
    public static function log(array $data): self
    {
        return self::create(array_merge([
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
            'currency'   => 'INR',
        ], $data));
    }

    public function getStatusBadgeAttribute(): array
    {
        return match ($this->status) {
            'success'  => ['bg' => 'bg-emerald-50 text-emerald-700 border-emerald-200', 'label' => 'Success', 'icon' => '🟢'],
            'failed'   => ['bg' => 'bg-rose-50 text-rose-700 border-rose-200', 'label' => 'Failed', 'icon' => '🔴'],
            'pending'  => ['bg' => 'bg-amber-50 text-amber-700 border-amber-200', 'label' => 'Pending', 'icon' => '🟡'],
            'refunded' => ['bg' => 'bg-purple-50 text-purple-700 border-purple-200', 'label' => 'Refunded', 'icon' => '↩️'],
            default    => ['bg' => 'bg-slate-100 text-slate-700 border-slate-200', 'label' => ucfirst($this->status), 'icon' => 'ℹ️'],
        };
    }
}
