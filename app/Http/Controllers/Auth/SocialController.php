<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\AuthService;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class SocialController extends Controller
{
    public function __construct(private AuthService $authService) {}

    /**
     * Redirect user to Google OAuth consent screen.
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Handle the callback from Google OAuth.
     */
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            return redirect()->route('auth.login')
                ->withErrors(['email' => 'Google login failed. Please try again.']);
        }

        $user = $this->authService->findOrCreateGoogleUser($googleUser);

        Auth::login($user, true);

        return redirect()->intended(route('frontend.home'))
            ->with('success', 'Google login successful! Welcome, ' . $user->name . '!');
    }
}
