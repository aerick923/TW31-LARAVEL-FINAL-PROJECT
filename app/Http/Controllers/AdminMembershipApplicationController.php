<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\MembershipApplication;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AdminMembershipApplicationController extends Controller
{
    // Display membership applications for administrators.
    public function index(Request $request): View
    {
        $query = MembershipApplication::query()
            ->with('category');

        if ($request->filled('status')) {
            $status = $request->validate([
                'status' => ['required', 'in:pending,approved,rejected'],
            ])['status'];

            $query->where('status', $status);
        }

        $applications = $query
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.applications.index', [
            'applications' => $applications,
        ]);
    }

    // Approve or reject a pending application.
    public function updateStatus(
        Request $request,
        MembershipApplication $application
    ): RedirectResponse {
        $validated = $request->validate([
            'status' => ['required', 'in:approved,rejected'],
        ]);

        DB::transaction(function () use ($application, $validated) {

            // Lock the application to prevent simultaneous review.
            $application = MembershipApplication::query()
                ->whereKey($application->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($application->status !== 'pending') {
                throw ValidationException::withMessages([
                    'status' => 'Only pending applications can be reviewed.',
                ]);
            }

            if ($validated['status'] === 'approved') {

                $category = $application->category;

                if (!$category) {
                    throw ValidationException::withMessages([
                        'status' => 'The membership category was not found.',
                    ]);
                }

                // Avoid automatically merging different member records.
                $existingMember = Member::where(
                    'email',
                    $application->email
                )->first();

                if ($existingMember) {
                    throw ValidationException::withMessages([
                        'status' => 'A member with this email already exists. Please review the existing record before approving.',
                    ]);
                }

                // Generate a unique membership number.
                do {
                    $membershipNumber = 'MEM-' .
                        strtoupper(Str::random(10));
                } while (
                    Member::where(
                        'membership_number',
                        $membershipNumber
                    )->exists()
                );

                // Create the member without activating membership.
                $member = Member::create([
                    'membership_number' => $membershipNumber,
                    'name' => $application->full_name,
                    'email' => $application->email,
                    'phone' => $application->phone,
                    'category' => $category->name,
                    'level' => $category->level ?: 'Standard',
                    'status' => 'Pending',
                ]);

                // Link the application to its member.
                $application->member_id = $member->id;
            }

            $application->status = $validated['status'];
            $application->save();
        });

        return redirect()
            ->route('admin.applications.index')
            ->with('success', 'Application status updated successfully!');
    }
}
