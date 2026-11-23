<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMembershipApplicationRequest;
use App\Models\MembershipApplication;
use App\Models\MembershipCategory;
use App\Models\MembershipLevel;
use App\Notifications\MembershipApplicationSubmitted;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\ValidationException;

class MembershipApplicationController extends Controller
{
    // Check whether the user already has a pending
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
        // Prevent duplicate pending or approved applications.
        if ($this->hasExistingApplication(auth()->id())) {
            return redirect()
                ->route('user.dashboard')
                ->with(
                    'success',
                    'You already have a pending or approved membership application. View its status on your dashboard.'
                );
        }

        // Retrieve categories and levels independently.
        return view('membership.create', [

            // Available membership categories.
            'categories' => MembershipCategory::query()
                ->where('is_active', true)
                ->whereIn('name', [
                    'Student',
                    'Regular',
                    'Senior Citizen',
                    'Corporate',
                ])
                ->orderByRaw("
                    CASE name
                        WHEN 'Student' THEN 1
                        WHEN 'Regular' THEN 2
                        WHEN 'Senior Citizen' THEN 3
                        WHEN 'Corporate' THEN 4
                        ELSE 5
                    END
                ")
                ->get(),

            // Available membership levels.
            'levels' => MembershipLevel::query()
                ->whereIn('name', [
                    'Basic',
                    'Silver',
                    'Gold',
                    'Platinum',
                ])
                ->orderByRaw("
                    CASE name
                        WHEN 'Basic' THEN 1
                        WHEN 'Silver' THEN 2
                        WHEN 'Gold' THEN 3
                        WHEN 'Platinum' THEN 4
                        ELSE 5
                    END
                ")
                ->get(),
        ]);
    }

    // Save a membership application for the authenticated user.
    public function store(
        StoreMembershipApplicationRequest $request
    ): RedirectResponse {

        $application = DB::transaction(function () use ($request) {

            // Lock the user record to prevent simultaneous
            // duplicate application submissions.
            $user = DB::table('users')
                ->where('id', $request->user()->id)
                ->lockForUpdate()
                ->first();

            if (!$user) {
                throw ValidationException::withMessages([
                    'membership_category_id' =>
                        'Your user account could not be found.',
                ]);
            }

            // Prevent duplicate pending or approved applications.
            if ($this->hasExistingApplication($request->user()->id)) {
                throw ValidationException::withMessages([
                    'membership_category_id' =>
                        'You already have a pending or approved membership application.',
                ]);
            }

            // Retrieve validated application information.
            // This includes the independently selected
            // membership_category_id and membership_level_id.
            $validated = $request->validated();

            // Generate a unique application reference number.
            do {
                $reference = 'MEM-' .
                    Str::upper(Str::random(10));

            } while (
                MembershipApplication::where(
                    'reference_no',
                    $reference
                )->exists()
            );

            // Create the membership application.
            // Do not create a member record at this stage.
            // Member creation happens after administrator approval.
            return MembershipApplication::create([
                ...$validated,
                'user_id' => $request->user()->id,
                'reference_no' => $reference,
                'status' => 'pending',
            ]);
        });

        // Send the application notification after saving.
        Notification::route('mail', $application->email)
            ->notify(
                new MembershipApplicationSubmitted($application)
            );

        // Record when the notification was sent.
        $application->update([
            'verification_sent_at' => now(),
        ]);

        // Return the user to their dashboard.
        return redirect()
            ->route('user.dashboard')
            ->with(
                'success',
                "Membership application submitted successfully! Reference: {$application->reference_no}"
            );
    }
}
