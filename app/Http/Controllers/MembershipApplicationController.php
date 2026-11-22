<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMembershipApplicationRequest;
use App\Models\MembershipApplication;
use App\Models\MembershipCategory;
use App\Notifications\MembershipApplicationSubmitted;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\ValidationException;

class MembershipApplicationController extends Controller
{
    // Check whether a user already has a pending
    // or approved membership application.
    private function hasExistingApplication(int $userId): bool
    {
        return MembershipApplication::query()
            ->where('user_id', $userId)
            ->whereIn('status', ['pending', 'approved'])
            ->exists();
    }

    // Display the membership application form.
    public function create(): View|RedirectResponse
    {
        if ($this->hasExistingApplication(auth()->id())) {
            return redirect()
                ->route('user.dashboard')
                ->with(
                    'success',
                    'You already have a pending or approved membership application. View its status on your dashboard.'
                );
        }

        return view('membership.create', [
            'categories' => MembershipCategory::where('is_active', true)
                ->orderBy('name')
                ->get(),
        ]);
    }

    // Save an application for the authenticated user.
    public function store(
        StoreMembershipApplicationRequest $request
    ): RedirectResponse {

        $application = DB::transaction(function () use ($request) {

            // Lock the user row to prevent simultaneous
            // duplicate application submissions.
            DB::table('users')
                ->where('id', $request->user()->id)
                ->lockForUpdate()
                ->first();

            if ($this->hasExistingApplication($request->user()->id)) {
                throw ValidationException::withMessages([
                    'membership_category_id' =>
                        'You already have a pending or approved membership application.',
                ]);
            }

            // Generate a unique application reference.
            do {
                $reference = 'MEM-' . Str::upper(Str::random(10));
            } while (
                MembershipApplication::where(
                    'reference_no',
                    $reference
                )->exists()
            );

            return MembershipApplication::create([
                ...$request->validated(),
                'user_id' => $request->user()->id,
                'reference_no' => $reference,
                'status' => 'pending',
            ]);
        });

        // Queue the email notification after saving.
        Notification::route('mail', $application->email)
            ->notify(new MembershipApplicationSubmitted($application));

        $application->update([
            'verification_sent_at' => now(),
        ]);

        return redirect()
            ->route('user.dashboard')
            ->with(
                'success',
                "Membership application submitted successfully! Reference: {$application->reference_no}"
            );
    }
}
