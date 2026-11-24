<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\MembershipLevel;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MemberController extends Controller
{
    // Display members with advanced search and filters.
    public function index(Request $request)
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
                Rule::in(['Active', 'Inactive']),
            ],
            'category' => [
                'nullable',
                Rule::in([
                    'Student',
                    'Regular',
                    'Senior Citizen',
                    'Corporate',
                ]),
            ],
            'level' => [
                'nullable',
                Rule::in([
                    'Basic',
                    'Silver',
                    'Gold',
                    'Platinum',
                ]),
            ],
        ]);

        // Retrieve members and related information.
        $query = Member::with([
            'membershipLevel',
            'membershipTransactions' => function ($query) {
                $query->where('status', 'Approved')
                    ->whereNotNull('start_date')
                    ->whereNotNull('end_date');
            },
        ]);

        // Search by name, email, membership number,
        // category, or membership level.
        if (!empty($filters['search'])) {
            $search = $filters['search'];

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere(
                        'membership_number',
                        'like',
                        "%{$search}%"
                    )
                    ->orWhere('category', 'like', "%{$search}%")
                    ->orWhere('level', 'like', "%{$search}%");
            });
        }

        // Filter by membership status.
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        // Filter by membership category.
        if (!empty($filters['category'])) {
            $query->where('category', $filters['category']);
        }

        // Filter by membership level.
        if (!empty($filters['level'])) {
            $query->where('level', $filters['level']);
        }

        // Paginate results while preserving filters.
        $members = $query
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('members.index', compact('members'));
    }

    // Display the member registration form.
    public function create()
    {
        $levels = MembershipLevel::orderBy('fee')->get();

        return view('members.create', compact('levels'));
    }

    // Register a new member.
    public function store(Request $request)
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
                Rule::unique('members', 'email'),
            ],
            'phone' => [
                'nullable',
                'string',
                'max:20',
            ],
            'category' => [
                'required',
                Rule::in([
                    'Student',
                    'Regular',
                    'Senior Citizen',
                    'Corporate',
                ]),
            ],
            'level' => [
                'required',
                Rule::in([
                    'Basic',
                    'Silver',
                    'Gold',
                    'Platinum',
                ]),
            ],
        ]);

        // Generate a unique membership number.
        do {
            $membershipNumber = 'MEM-' . strtoupper(
                bin2hex(random_bytes(5))
            );
        } while (
            Member::where(
                'membership_number',
                $membershipNumber
            )->exists()
        );

        $validated['membership_number'] = $membershipNumber;
        $validated['status'] = 'Active';

        Member::create($validated);

        return redirect()
            ->route('members.index')
            ->with(
                'success',
                'Member registered successfully!'
            );
    }

    // Display a member's profile.
    public function show(Member $member)
    {
        return view('members.show', compact('member'));
    }

    // Display the member editing form.
    public function edit(Member $member)
    {
        $levels = MembershipLevel::orderBy('fee')->get();

        return view('members.edit', compact(
            'member',
            'levels'
        ));
    }

    // Update an existing member.
    public function update(Request $request, Member $member)
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
                Rule::unique('members', 'email')
                    ->ignore($member->id),
            ],
            'phone' => [
                'nullable',
                'string',
                'max:20',
            ],
            'category' => [
                'required',
                Rule::in([
                    'Student',
                    'Regular',
                    'Senior Citizen',
                    'Corporate',
                ]),
            ],
            'level' => [
                'required',
                Rule::in([
                    'Basic',
                    'Silver',
                    'Gold',
                    'Platinum',
                ]),
            ],
        ]);

        $member->update($validated);

        return redirect()
            ->route('members.index')
            ->with(
                'success',
                'Member updated successfully!'
            );
    }

    // Delete a member only if no transactions exist.
    public function destroy(Member $member)
    {
        if ($member->membershipTransactions()->exists()) {
            return redirect()
                ->route('members.index')
                ->with(
                    'error',
                    'This member has transaction history and cannot be deleted. Please deactivate the member instead.'
                );
        }

        $member->delete();

        return redirect()
            ->route('members.index')
            ->with(
                'success',
                'Member deleted successfully!'
            );
    }

    // Deactivate or reactivate a member.
    public function toggleStatus(Member $member)
    {
        $member->update([
            'status' => $member->status === 'Active'
                ? 'Inactive'
                : 'Active',
        ]);

        return redirect()
            ->route('members.index')
            ->with(
                'success',
                'Member status updated successfully!'
            );
    }
}
