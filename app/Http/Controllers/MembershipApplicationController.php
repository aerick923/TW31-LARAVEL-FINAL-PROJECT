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

class MembershipApplicationController extends Controller
{
    // Display the membership application form.
    public function create(): View
    {
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

            $application = MembershipApplication::create([
                ...$request->validated(),

                // Associate the application with its owner.
                'user_id' => $request->user()->id,

                'reference_no' => 'MEM-' . Str::upper(Str::random(10)),
            ]);

            return $application;
        });

        // Send the notification after saving the application.
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
