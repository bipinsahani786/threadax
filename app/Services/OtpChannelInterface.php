<?php

namespace App\Services;

interface OtpChannelInterface
{
    /**
     * Send the OTP code to the given target (email, phone number, etc.)
     *
     * @param string $target
     * @param string $otp
     * @return bool
     */
    public function send(string $target, string $otp): bool;
}
