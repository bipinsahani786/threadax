<?php

namespace App\Services;

use App\Jobs\SendOtpEmail;

class EmailOtpChannel implements OtpChannelInterface
{
    public function send(string $target, string $otp): bool
    {
        // Dispatch the job to send the email
        SendOtpEmail::dispatch($target, $otp);
        return true;
    }
}
