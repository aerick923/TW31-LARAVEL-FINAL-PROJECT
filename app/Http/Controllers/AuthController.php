<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    // Display the login page.
    public function showLogin()
    {
        return view('auth.login');
    }

    // Authenticate regular users and administrators.
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        // Authenticate using the stored account credentials.
        if (!Auth::attempt($credentials)) {
            throw ValidationException::withMessages([
                'email' => 'The provided credentials are incorrect.',
            ]);
        }

        // Prevent session fixation.
        $request->session()->regenerate();

        $user = Auth::user();

        // Administrators go to the administrator dashboard.
        if ($user->role === 'admin') {
            return redirect()
                ->route('dashboard.index')
                ->with('success', 'Administrator login successful!');
        }

        // Regular users must verify their email first.
        if ($user->role === 'user') {

            if (!$user->hasVerifiedEmail()) {
                return redirect()
                    ->route('verification.notice');
            }

            return redirect()
                ->route('user.dashboard')
                ->with('success', 'Logged in successfully!');
        }

        // Reject accounts with unexpected roles.
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        throw ValidationException::withMessages([
            'email' => 'This account does not have a valid role.',
        ]);
    }

    // Securely log out the authenticated account.
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('login')
            ->with('success', 'Logged out successfully!');
    }
}
