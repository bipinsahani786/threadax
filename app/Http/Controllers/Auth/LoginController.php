<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Show the login page.
     */
    public function show()
    {
        if (Auth::check()) {
            return redirect()->route('frontend.home');
        }

        return view('auth.login');
    }

    /**
     * Log the user out.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('auth.login')
            ->with('success', 'You have been successfully logged out.');
    }
}
