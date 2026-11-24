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
