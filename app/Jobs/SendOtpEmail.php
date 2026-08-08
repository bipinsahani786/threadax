<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class SendOtpEmail implements ShouldQueue
{
    use Queueable;

    protected $target;
    protected $otp;

    /**
     * Create a new job instance.
     */
    public function __construct(string $target, string $otp)
    {
        $this->target = $target;
        $this->otp = $otp;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        \Illuminate\Support\Facades\Mail::to($this->target)->send(new \App\Mail\OtpMail($this->otp));
    }
}
