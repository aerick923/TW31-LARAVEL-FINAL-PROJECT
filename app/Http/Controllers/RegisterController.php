<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{
    // Display the registration form.
    public function showRegistration()
    {
        return view('auth.register');
    }

    // Register a new user account.
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],
            'email' => [
                'required',
                'email',
                'max:255',
                'unique:users,email',
            ],
            'password' => [
                'required',
                'confirmed',
                Password::min(8)->mixedCase()->numbers(),
            ],
        ]);

        // Public registration creates ordinary users only.
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'],
        ]);

        // Trigger Laravel's email verification notification.
        event(new Registered($user));

        // Log in the newly registered user.
        Auth::login($user);

        // Regenerate the session for security.
        $request->session()->regenerate();

        // Require email verification before dashboard access.
        return redirect()
            ->route('verification.notice');
    }
}
