<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\AuthService;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;

class SocialController extends Controller
{
    public function __construct(private AuthService $authService) {}

    /**
     * Redirect user to Google OAuth consent screen.
     */
    public function redirectToGoogle()
    {
        $guzzle = new Client(['verify' => false]);
        return Socialite::driver('google')->setHttpClient($guzzle)->redirect();
    }

    /**
     * Handle the callback from Google OAuth.
     */
    public function handleGoogleCallback()
    {
        try {
            $guzzle = new Client(['verify' => false]);
            $googleUser = Socialite::driver('google')->setHttpClient($guzzle)->user();
        } catch (\Exception $e) {
            Log::error('Google OAuth callback failed: ' . $e->getMessage(), [
                'exception' => $e
            ]);
            return redirect()->route('auth.login')
                ->withErrors(['email' => 'Google login failed: ' . $e->getMessage()]);
        }

        $user = $this->authService->findOrCreateGoogleUser($googleUser);

        Auth::login($user, true);

        return redirect()->intended(route('frontend.home'))
            ->with('success', 'Google login successful! Welcome, ' . $user->name . '!');
    }
}

