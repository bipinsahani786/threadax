<?php

namespace App\Services;

use App\Models\User;
use Laravel\Socialite\Contracts\User as SocialiteUser;

class AuthService
{
    /**
     * Handle the Socialite user returned from Google.
     *
     * @param SocialiteUser $googleUser
     * @return User
     */
    public function handleGoogleUser(SocialiteUser $googleUser): User
    {
        // Check if user exists by google_id or email
        $user = User::where('google_id', $googleUser->id)
            ->orWhere('email', $googleUser->email)
            ->first();

        if ($user) {
            // Update google_id and avatar if missing/changed
            $user->update([
                'google_id' => $googleUser->id,
                'avatar' => $googleUser->avatar,
                'email_verified_at' => $user->email_verified_at ?? now(),
            ]);
        } else {
            // Create a new user
            $user = User::create([
                'name' => $googleUser->name ?? $googleUser->nickname ?? 'User',
                'email' => $googleUser->email,
                'google_id' => $googleUser->id,
                'avatar' => $googleUser->avatar,
                'email_verified_at' => now(), // Google emails are already verified
            ]);
        }

        return $user;
    }
}
