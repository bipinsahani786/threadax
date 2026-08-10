<?php

namespace App\Traits;

use App\Models\OtpCode;
use Illuminate\Support\Str;

trait HasOtp
{
    /**
     * Generate a new OTP for the user's email.
     */
    public function generateOtp(string $channel = 'email'): OtpCode
    {
        // Invalidate existing OTPs first
        $this->invalidateOtps();

        return OtpCode::create([
            'email'      => $this->email,
            'code'       => str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT),
            'channel'    => $channel,
            'expires_at' => now()->addMinutes(10),
            'used'       => false,
        ]);
    }

    /**
     * Verify an OTP code for this user's email.
     */
    public function verifyOtp(string $code): bool
    {
        $otp = OtpCode::where('email', $this->email)
            ->where('code', $code)
            ->where('used', false)
            ->where('expires_at', '>', now())
            ->first();

        if (! $otp) {
            return false;
        }

        $otp->update(['used' => true]);

        // Mark email as verified
        if (! $this->email_verified_at) {
            $this->update(['email_verified_at' => now()]);
        }

        return true;
    }

    /**
     * Invalidate all existing OTPs for this user.
     */
    public function invalidateOtps(): void
    {
        OtpCode::where('email', $this->email)
            ->where('used', false)
            ->update(['used' => true]);
    }
}
