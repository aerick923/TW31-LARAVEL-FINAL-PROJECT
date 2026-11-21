<?php

namespace App\Http\Controllers;

use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EmailVerificationController extends Controller
{
    // Display the email verification notice.
    public function notice(): View
    {
        return view('auth.verify-email');
    }

    // Verify the user's email address.
    public function verify(
        EmailVerificationRequest $request
    ): RedirectResponse {

        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->route('user.dashboard');
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        return redirect()
            ->route('user.dashboard')
            ->with(
                'success',
                'Your email address has been verified successfully!'
            );
    }

    // Resend the email verification notification.
    public function resend(Request $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->route('user.dashboard');
        }

        $request->user()->sendEmailVerificationNotification();

        return back()->with(
            'success',
            'A new verification link has been sent to your email.'
        );
    }
}
