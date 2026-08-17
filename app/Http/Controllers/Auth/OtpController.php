<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\OtpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OtpController extends Controller
{
    public function __construct(private OtpService $otpService) {}

    /**
     * Send OTP to the provided email.
     */
    public function send(Request $request)
    {
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'email' => ['required', 'email', 'max:255'],
        ]);

        if ($validator->fails()) {
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
            }
            return back()->withErrors($validator)->withInput();
        }

        $result = $this->otpService->sendOtp($request->email);

        if (! $result['success']) {
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $result['message']], 422);
            }
            return back()->withErrors(['email' => $result['message']])->withInput();
        }

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => $result['message'],
                'email'   => $request->email,
            ]);
        }

        return redirect()
            ->route('auth.otp.verify.form', ['email' => $request->email])
            ->with('success', $result['message']);
    }

    /**
     * Show OTP verification form.
     */
    public function verifyForm(Request $request)
    {
        $email = $request->query('email');

        if (! $email) {
            return redirect()->route('auth.login');
        }

        return view('auth.otp-verify', compact('email'));
    }

    /**
     * Verify the submitted OTP and log the user in.
     */
    public function verify(Request $request)
    {
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'email' => ['required', 'email'],
            'code'  => ['required', 'digits:6'],
        ]);

        if ($validator->fails()) {
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
            }
            return back()->withErrors($validator)->withInput();
        }

        $result = $this->otpService->verifyOtp($request->email, $request->code);

        if (! $result['success']) {
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $result['message']], 422);
            }
            return back()->withErrors(['code' => $result['message']])->withInput();
        }

        $guestSessionId = \Illuminate\Support\Facades\Session::getId();
        $guestCartToken = $request->cookie(\App\Services\CartService::GUEST_COOKIE_NAME);

        Auth::login($result['user'], true);

        // Merge guest cart items into user's account cart
        try {
            app(\App\Services\CartService::class)->mergeGuestCart($result['user']->id, $guestSessionId, $guestCartToken);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::warning("Guest cart merge failed on OTP login: " . $e->getMessage());
        }

        if ($request->wantsJson()) {
            return response()->json([
                'success'  => true,
                'message'  => 'Login successful! Welcome to ThreadAX.',
                'redirect' => session()->get('url.intended', route('frontend.home')),
            ]);
        }

        return redirect()->intended(route('frontend.home'))
            ->with('success', 'Login successful! Welcome to ThreadAX.');
    }
}
