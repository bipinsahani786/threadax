<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\OtpService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;

class OtpController extends Controller
{
    public function send(Request $request, OtpService $otpService)
    {
        $request->validate(['email' => 'required|email']);
        $email = $request->email;

        // Rate limiting: max 3 OTPs per email per 10 minutes
        $key = 'send-otp:' . $email;
        if (RateLimiter::tooManyAttempts($key, 3)) {
            $seconds = RateLimiter::availableIn($key);
            return back()->withErrors(['email' => "Too many attempts. Try again in {$seconds} seconds."]);
        }

        RateLimiter::hit($key, 600); // 10 minutes

        $otpService->sendOtp($email, 'email');

        return back()->with('otp_sent', true)->with('email', $email);
    }

    public function verify(Request $request, OtpService $otpService)
    {
        $request->validate([
            'email' => 'required|email',
            'code' => 'required|string|size:6',
        ]);

        $user = $otpService->verifyOtp($request->email, $request->code, 'email');

        if ($user) {
            Auth::login($user);
            $request->session()->regenerate();
            return redirect()->intended('/account/dashboard');
        }

        return back()->withErrors(['code' => 'Invalid or expired OTP.'])->with('otp_sent', true)->with('email', $request->email);
    }
}
