<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OtpCode extends Model
{
    protected $fillable = [
        'email',
        'phone',
        'code',
        'channel',
        'expires_at',
        'used',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'used'       => 'boolean',
    ];

    /**
     * Check if this OTP has expired.
     */
    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }

    /**
     * Check if this OTP is still valid.
     */
    public function isValid(): bool
    {
        return ! $this->used && ! $this->isExpired();
    }
}
