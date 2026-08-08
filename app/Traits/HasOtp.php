<?php

namespace App\Traits;

use App\Models\OtpCode;
use Carbon\Carbon;

trait HasOtp
{
    /**
     * Generate a new OTP code for this user.
     *
     * @param string $channel (e.g., 'email', 'sms')
     * @return OtpCode
     */
    public function generateOtp(string $channel = 'email'): OtpCode
    {
        // Invalidate previous active OTPs for this user & channel
        $this->invalidateOtps($channel);

        // Generate a random 6-digit code
        $code = str_pad((string)random_int(100000, 999999), 6, '0', STR_PAD_LEFT);

        // Create the new OTP record
        return OtpCode::create([
            'email' => $this->email,
            'phone' => $this->phone,
            'code' => $code,
            'channel' => $channel,
            'expires_at' => Carbon::now()->addMinutes(5),
            'used' => false,
        ]);
    }

    /**
     * Verify a given OTP code.
     *
     * @param string $code
     * @param string $channel
     * @return bool
     */
    public function verifyOtp(string $code, string $channel = 'email'): bool
    {
        $otp = OtpCode::where('email', $this->email)
            ->where('channel', $channel)
            ->where('code', $code)
            ->where('used', false)
            ->where('expires_at', '>', Carbon::now())
            ->first();

        if ($otp) {
            $otp->update(['used' => true]);
            return true;
        }

        return false;
    }

    /**
     * Invalidate all active OTPs for this user.
     *
     * @param string|null $channel
     */
    public function invalidateOtps(?string $channel = null): void
    {
        $query = OtpCode::where('email', $this->email)
            ->where('used', false)
            ->where('expires_at', '>', Carbon::now());

        if ($channel) {
            $query->where('channel', $channel);
        }

        $query->update(['used' => true]);
    }
}
