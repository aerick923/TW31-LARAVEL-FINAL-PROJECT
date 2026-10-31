<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\MembershipLevel;

class MemberController extends Controller
{
    // Display and search members
    public function index(Request $request)
    {
        $query = Member::with('membershipLevel');

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%")
                  ->orWhere('level', 'like', "%{$search}%");
            });
        }

        $members = $query->latest()->paginate(10);

        return view('members.index', compact('members'));
    }

    // Show registration form
    
public function create()
{
    $levels = MembershipLevel::orderBy('fee')->get();

    return view('members.create', compact('levels'));
}


    // Save a new member
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:members,email',
            'phone' => 'nullable|string|max:20',
            'category' => [
                'required',
                Rule::in(['Student', 'Regular', 'Senior Citizen', 'Corporate'])
            ],
            'level' => [
                'required',
                Rule::in(['Basic', 'Silver', 'Gold', 'Platinum'])
            ],
        ]);

        // Generate a unique membership number
        do {
            $membershipNumber = 'MEM-' . strtoupper(
                bin2hex(random_bytes(5))
            );
        } while (Member::where(
            'membership_number',
            $membershipNumber
        )->exists());

        $validated['membership_number'] = $membershipNumber;
        $validated['status'] = 'Active';

        Member::create($validated);

        return redirect()->route('members.index')
            ->with('success', 'Member registered successfully!');
    }

    // View member profile
    public function show(Member $member)
    {
        return view('members.show', compact('member'));
    }

    // Show edit form
    
public function edit(Member $member)
{
    $levels = MembershipLevel::orderBy('fee')->get();

    return view('members.edit', compact('member', 'levels'));
}


    // Update member information
    public function update(Request $request, Member $member)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('members', 'email')->ignore($member->id)
            ],
            'phone' => 'nullable|string|max:20',
            'category' => [
                'required',
                Rule::in(['Student', 'Regular', 'Senior Citizen', 'Corporate'])
            ],
            'level' => [
                'required',
                Rule::in(['Basic', 'Silver', 'Gold', 'Platinum'])
            ],
        ]);

        $member->update($validated);

        return redirect()->route('members.index')
            ->with('success', 'Member updated successfully!');
    }

    // Permanently delete a member
    public function destroy(Member $member)
    {
        $member->delete();

        return redirect()->route('members.index')
            ->with('success', 'Member deleted successfully!');
    }

    // Deactivate or reactivate a member
    public function toggleStatus(Member $member)
    {
        $member->update([
            'status' => $member->status === 'Active'
                ? 'Inactive'
                : 'Active'
        ]);

        return redirect()->route('members.index')
            ->with('success', 'Member status updated successfully!');
    }
}
