<?php

namespace App\Http\Controllers;

use App\Models\MembershipLevel;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class LevelController extends Controller
{
    // Display all membership levels
    public function index()
    {
        $levels = MembershipLevel::orderBy('fee')->get();

        return view('levels.index', compact('levels'));
    }

    // Show the level creation form
    public function create()
    {
        return view('levels.create');
    }

    // Save a new membership level
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('membership_levels', 'name'),
            ],
            'fee' => [
                'required',
                'numeric',
                'min:0',
                'max:99999999.99',
            ],
            'validity_months' => [
                'required',
                'integer',
                'min:1',
                'max:120',
            ],
            'benefits' => [
                'nullable',
                'string',
                'max:5000',
            ],
        ]);

        $level = new MembershipLevel();

        $level->name = $validated['name'];
        $level->fee = $validated['fee'];
        $level->validity_months = $validated['validity_months'];
        $level->benefits = $validated['benefits'] ?? null;

        $level->save();

        return redirect()
            ->route('levels.index')
            ->with('success', 'Membership level created successfully!');
    }

    // Show the edit form
    public function edit(MembershipLevel $level)
    {
        return view('levels.edit', compact('level'));
    }

    // Update an existing membership level
    public function update(Request $request, MembershipLevel $level)
    {
        $validated = $request->validate([
            
'name' => [
    'required',
    'string',
    'max:255',
    Rule::in([$level->name]),
],

            'fee' => [
                'required',
                'numeric',
                'min:0',
                'max:99999999.99',
            ],
            'validity_months' => [
                'required',
                'integer',
                'min:1',
                'max:120',
            ],
            'benefits' => [
                'nullable',
                'string',
                'max:5000',
            ],
        ]);

        $level->name = $validated['name'];
        $level->fee = $validated['fee'];
        $level->validity_months = $validated['validity_months'];
        $level->benefits = $validated['benefits'] ?? null;

        $level->save();

        return redirect()
            ->route('levels.index')
            ->with('success', 'Membership level updated successfully!');
    }

    // Prevent deleting levels used by members
    
public function destroy(MembershipLevel $level)
{
    $requiredLevels = [
        'Basic',
        'Silver',
        'Gold',
        'Platinum',
    ];

    if (in_array($level->name, $requiredLevels, true)) {
        return redirect()
            ->route('levels.index')
            ->with(
                'error',
                'Required membership levels cannot be deleted.'
            );
    }

    $isUsed = \App\Models\Member::where(
        'level',
        $level->name
    )->exists();

    if ($isUsed) {
        return redirect()
            ->route('levels.index')
            ->with(
                'error',
                'This membership level is assigned to existing members and cannot be deleted.'
            );
    }

    return redirect()
        ->route('levels.index')
        ->with(
            'error',
            'Deleting membership levels is disabled to protect existing records.'
        );
}

}
