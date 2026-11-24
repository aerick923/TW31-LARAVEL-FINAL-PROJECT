<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\MembershipApplication;
use App\Models\MembershipCategory;
use App\Models\MembershipLevel;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rule;
use App\Models\User;

class AdminMembershipApplicationController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | DISPLAY AND FILTER MEMBERSHIP APPLICATIONS
    |--------------------------------------------------------------------------
    */

    public function index(Request $request): View
    {
        // Validate search and filter inputs.
        $filters = $request->validate([
            'search' => [
                'nullable',
                'string',
                'max:255',
            ],
            'status' => [
                'nullable',
                'in:pending,approved,rejected',
            ],
            'category' => [
                'nullable',
                'integer',
                Rule::exists('membership_categories', 'id'),
            ],
            'level' => [
                'nullable',
                'integer',
                Rule::exists('membership_levels', 'id'),
            ],
        ]);

        // Retrieve applications with their relationships.
        $query = MembershipApplication::query()
            ->with([
                'category',
                'membershipLevel',
            ]);

        // Search applications.
        if (!empty($filters['search'])) {
            $search = $filters['search'];

            $query->where(function ($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere(
                        'reference_no',
                        'like',
                        "%{$search}%"
                    );
            });
        }

        // Filter by application status.
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        // Filter by membership category.
        if (!empty($filters['category'])) {
            $query->where(
                'membership_category_id',
                $filters['category']
            );
        }

        // Filter by membership level.
        if (!empty($filters['level'])) {
            $query->where(
                'membership_level_id',
                $filters['level']
            );
        }

        // Paginate results while preserving filters.
        $applications = $query
            ->latest()
            ->paginate(10)
            ->withQueryString();

        // Retrieve available categories and levels.
        $categories = MembershipCategory::query()
            ->orderBy('name')
            ->get();

        $levels = MembershipLevel::query()
            ->orderBy('name')
            ->get();

        return view(
            'admin.applications.index',
            compact(
                'applications',
                'categories',
                'levels'
            )
        );
    }

    /*
|--------------------------------------------------------------------------
| DISPLAY ADMINISTRATOR APPLICATION FORM
|--------------------------------------------------------------------------
*/

public function create(): View
{
    // Retrieve verified regular users who do not already
    // have a pending or approved membership application.
    $users = User::query()
        ->where('role', 'user')
        ->whereNotNull('email_verified_at')
        ->whereDoesntHave('membershipApplications', function ($query) {
            $query->whereIn('status', [
                'pending',
                'approved',
            ]);
        })
        ->orderBy('name')
        ->get();

    // Retrieve active membership categories.
    $categories = MembershipCategory::query()
        ->where('is_active', true)
        ->orderBy('name')
        ->get();

    // Retrieve membership levels.
    $levels = MembershipLevel::query()
        ->orderBy('name')
        ->get();

    return view('admin.applications.create', compact(
        'users',
        'categories',
        'levels'
    ));
}


/*
|--------------------------------------------------------------------------
| STORE ADMINISTRATOR-CREATED MEMBERSHIP APPLICATION
|--------------------------------------------------------------------------
*/

public function store(Request $request): RedirectResponse
{
    $validated = $request->validate([
        'user_id' => [
            'required',
            'integer',
            Rule::exists('users', 'id')
                ->where('role', 'user')
                ->whereNotNull('email_verified_at'),
        ],
        'membership_category_id' => [
            'required',
            'integer',
            Rule::exists('membership_categories', 'id')
                ->where('is_active', 1),
        ],
        'membership_level_id' => [
            'required',
            'integer',
            Rule::exists('membership_levels', 'id'),
        ],
        'full_name' => ['required', 'string', 'max:150'],
        'phone' => [
            'required',
            'string',
            'regex:/^09[0-9]{9}$/',
        ],
        'address' => ['required', 'string', 'max:255'],
        'latitude' => ['required', 'numeric', 'between:-90,90'],
        'longitude' => ['required', 'numeric', 'between:-180,180'],
    ]);

    $application = DB::transaction(function () use ($validated) {

        // Lock the selected user to prevent simultaneous submissions.
        $user = \App\Models\User::query()
            ->whereKey($validated['user_id'])
            ->lockForUpdate()
            ->firstOrFail();

        // Verify eligibility again inside the transaction.
        if ($user->role !== 'user' || !$user->hasVerifiedEmail()) {
            throw ValidationException::withMessages([
                'user_id' => 'Please select a verified regular user.',
            ]);
        }

        // Prevent duplicate pending or approved applications.
        if ($user->membershipApplications()
            ->whereIn('status', ['pending', 'approved'])
            ->exists()) {
            throw ValidationException::withMessages([
                'user_id' =>
                    'This user already has a pending or approved application.',
            ]);
        }

        // Prevent creating an application for an existing member.
        if (Member::where('email', $user->email)->exists()) {
            throw ValidationException::withMessages([
                'user_id' =>
                    'This user already has a member record.',
            ]);
        }

        // Generate a unique application reference.
        do {
            $reference = 'MEM-' . Str::upper(Str::random(10));
        } while (
            MembershipApplication::where('reference_no', $reference)
                ->exists()
        );

        return MembershipApplication::create([
            'user_id' => $user->id,
            'member_id' => null,
            'membership_category_id' =>
                $validated['membership_category_id'],
            'membership_level_id' =>
                $validated['membership_level_id'],
            'reference_no' => $reference,
            'full_name' => $validated['full_name'],
            'email' => $user->email,
            'phone' => $validated['phone'],
            'address' => $validated['address'],
            'latitude' => $validated['latitude'],
            'longitude' => $validated['longitude'],
            'status' => 'pending',
        ]);
    });

    return redirect()
        ->route('admin.applications.index')
        ->with(
            'success',
            "Application recorded successfully! Reference: {$application->reference_no}"
        );
}


    /*
    |--------------------------------------------------------------------------
    | VIEW AN INDIVIDUAL MEMBERSHIP APPLICATION
    |--------------------------------------------------------------------------
    */

    public function show(MembershipApplication $application): View
    {
        // Load the application category, membership level,
        // and linked member record.
        $application->load([
            'category',
            'membershipLevel',
            'member',
        ]);

        // Display the application review page.
        return view(
            'admin.applications.show',
            compact('application')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | APPROVE OR REJECT A MEMBERSHIP APPLICATION
    |--------------------------------------------------------------------------
    */

    public function updateStatus(
        Request $request,
        MembershipApplication $application
    ): RedirectResponse {

        $validated = $request->validate([
            'status' => [
                'required',
                'in:approved,rejected',
            ],
        ]);

        DB::transaction(function () use ($application, $validated) {

            // Lock the application to prevent simultaneous review.
            $application = MembershipApplication::query()
                ->whereKey($application->id)
                ->lockForUpdate()
                ->firstOrFail();

            // Only pending applications can be reviewed.
            if ($application->status !== 'pending') {
                throw ValidationException::withMessages([
                    'status' =>
                        'Only pending applications can be reviewed.',
                ]);
            }

            if ($validated['status'] === 'approved') {

                // Retrieve the selected membership category.
                $category = $application->category;

                if (!$category) {
                    throw ValidationException::withMessages([
                        'status' =>
                            'The membership category was not found.',
                    ]);
                }

                // Retrieve the selected membership level.
                $membershipLevel = $application->membershipLevel;

                if (!$membershipLevel) {
                    throw ValidationException::withMessages([
                        'status' =>
                            'This application does not have a valid membership level. Please review the application before approving.',
                    ]);
                }

                // Prevent duplicate member records.
                $existingMember = Member::where(
                    'email',
                    $application->email
                )->first();

                if ($existingMember) {
                    throw ValidationException::withMessages([
                        'status' =>
                            'A member with this email already exists. Please review the existing record before approving.',
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

                // Create a member using the selected category
                // and membership level.
                $member = Member::create([
                    'membership_number' => $membershipNumber,
                    'name' => $application->full_name,
                    'email' => $application->email,
                    'phone' => $application->phone,
                    'category' => $category->name,
                    'level' => $membershipLevel->name,
                    'status' => 'Pending',
                ]);

                // Link the approved application to its member.
                $application->member_id = $member->id;
            }

            // Save the administrator's decision.
            $application->status = $validated['status'];
            $application->save();
        });

        return redirect()
            ->route('admin.applications.index')
            ->with(
                'success',
                'Application status updated successfully!'
            );
    }
}
