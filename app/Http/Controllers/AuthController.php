<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    // Display the administrator login page.
    public function showLogin()
    {
        return view('auth.login');
    }

    // Authenticate the administrator.
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (!Auth::attempt($credentials)) {
            throw ValidationException::withMessages([
                'email' => 'The provided credentials are incorrect.',
            ]);
        }

        // Prevent session fixation.
        $request->session()->regenerate();

        return redirect()
            ->intended(route('transactions.index'))
            ->with('success', 'Logged in successfully!');
    }

    // Log out the administrator.
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
