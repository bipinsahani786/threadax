<?php

namespace App\Services;

use App\Models\User;

class OtpService
{
    /**
     * Send an OTP to the given target (email/phone) using the given channel.
     *
     * @param string $target
     * @param string $channel
     * @return bool
     */
    public function sendOtp(string $target, string $channel = 'email'): bool
    {
        // 1. Find or create user
        // Note: For passwordless login via email, we create a placeholder user if they don't exist.
        // Or we just find them. Depending on business logic, we might just store OTP without creating a user
        // until they verify. But since HasOtp is on User model, we'll find or create the user.
        $user = User::firstOrCreate(
            ['email' => $target],
            ['name' => explode('@', $target)[0], 'password' => null]
        );

        // 2. Generate OTP
        $otp = $user->generateOtp($channel);

        // 3. Resolve channel implementation and send
        $channelImpl = $this->resolveChannel($channel);
        return $channelImpl->send($target, $otp->code);
    }

    /**
     * Verify the given OTP.
     *
     * @param string $target
     * @param string $code
     * @param string $channel
     * @return User|null
     */
    public function verifyOtp(string $target, string $code, string $channel = 'email'): ?User
    {
        $user = User::where('email', $target)->first();

        if ($user && $user->verifyOtp($code, $channel)) {
            return $user;
        }

        return null;
    }

    /**
     * Resolve the channel implementation.
     *
     * @param string $channel
     * @return OtpChannelInterface
     * @throws \Exception
     */
    protected function resolveChannel(string $channel): OtpChannelInterface
    {
        return match ($channel) {
            'email' => new EmailOtpChannel(),
            // 'sms' => new SmsOtpChannel(), // Future implementation
            default => throw new \Exception("Unsupported OTP channel: {$channel}"),
        };
    }
}
