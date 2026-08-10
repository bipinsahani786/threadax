<?php

namespace App\Services;

use App\Mail\OtpMail;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;

class OtpService
{
    /**
     * Send OTP to the given email address.
     * Rate limited: max 3 attempts per 10 minutes.
     */
    public function sendOtp(string $email): array
    {
        $rateLimitKey = 'otp_send:' . $email;

        if (RateLimiter::tooManyAttempts($rateLimitKey, 3)) {
            $seconds = RateLimiter::availableIn($rateLimitKey);
            return [
                'success' => false,
                'message' => "Too many attempts. Please try again in {$seconds} seconds.",
            ];
        }

        RateLimiter::hit($rateLimitKey, 600); // 10 minutes

        // Find or create user by email
        $user = User::firstOrCreate(
            ['email' => $email],
            ['name'  => explode('@', $email)[0]] // Default name from email
        );

        // Generate OTP
        $otp = $user->generateOtp('email');

        // Send email (queued)
        Mail::to($email)->queue(new OtpMail($otp->code));

        return [
            'success' => true,
            'message' => "A 6-digit OTP has been sent to {$email}.",
        ];
    }

    /**
     * Verify an OTP for the given email.
     */
    public function verifyOtp(string $email, string $code): array
    {
        $rateLimitKey = 'otp_verify:' . $email;

        if (RateLimiter::tooManyAttempts($rateLimitKey, 5)) {
            return [
                'success' => false,
                'message' => 'Too many failed attempts. Please try again later.',
            ];
        }

        $user = User::where('email', $email)->first();

        if (! $user) {
            return ['success' => false, 'message' => 'User account not found.'];
        }

        if (! $user->verifyOtp($code)) {
            RateLimiter::hit($rateLimitKey, 300); // 5 minutes
            return ['success' => false, 'message' => 'Invalid or expired OTP. Please try again.'];
        }

        RateLimiter::clear($rateLimitKey);

        return ['success' => true, 'user' => $user];
    }
}
