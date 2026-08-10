<?php

namespace App\Services;

use App\Models\User;
use Laravel\Socialite\Contracts\User as SocialiteUser;

class AuthService
{
    /**
     * Find or create a user from Google OAuth callback data.
     */
    public function findOrCreateGoogleUser(SocialiteUser $googleUser): User
    {
        // Try to find by Google ID first
        $user = User::where('google_id', $googleUser->getId())->first();

        if ($user) {
            // Update avatar in case it changed
            $user->update(['avatar' => $googleUser->getAvatar()]);
            return $user;
        }

        // Try to find by email (user may have registered with OTP before)
        $user = User::where('email', $googleUser->getEmail())->first();

        if ($user) {
            $user->update([
                'google_id'         => $googleUser->getId(),
                'avatar'            => $googleUser->getAvatar(),
                'email_verified_at' => $user->email_verified_at ?? now(),
            ]);
            return $user;
        }

        // Create new user
        return User::create([
            'name'              => $googleUser->getName(),
            'email'             => $googleUser->getEmail(),
            'google_id'         => $googleUser->getId(),
            'avatar'            => $googleUser->getAvatar(),
            'email_verified_at' => now(), // Google already verified the email
        ]);
    }
}
