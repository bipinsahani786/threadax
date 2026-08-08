<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Services\AuthService;
use Illuminate\Support\Facades\Auth;

class SocialController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback(AuthService $authService)
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            return redirect()->route('auth.login')->withErrors(['oauth' => 'Google Login failed or was cancelled.']);
        }

        $user = $authService->handleGoogleUser($googleUser);

        Auth::login($user);
        session()->regenerate();

        return redirect()->intended('/account/dashboard');
    }
}
