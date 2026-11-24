<?php

namespace App\Http\Controllers;

use App\Models\MembershipCategory;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    // Display all membership categories.
    public function index()
    {
        $categories = MembershipCategory::query()
            ->orderBy('name')
            ->get();

        return view('categories.index', compact('categories'));
    }

    // Display the category creation form.
    public function create()
    {
        return view('categories.create');
    }

    // Save a new membership category.

public function store(Request $request)
{
    $validated = $request->validate([
        'name' => [
            'required',
            'string',
            'max:255',
            Rule::unique('membership_categories', 'name'),
        ],
        'description' => [
            'nullable',
            'string',
            'max:5000',
        ],
        'level' => [
            'nullable',
            'string',
            'max:255',
        ],
        'is_active' => [
            'required',
            'boolean',
        ],
    ]);

    MembershipCategory::create([
        'name' => $validated['name'],
        'description' => $validated['description'] ?? null,
        'level' => $validated['level'] ?? null,
        'fee' => 0,
        'is_active' => $validated['is_active'],
    ]);

    return redirect()
        ->route('categories.index')
        ->with(
            'success',
            'Membership category created successfully!'
        );
}


    // Display the category editing form.
    public function edit(MembershipCategory $category)
    {
        return view('categories.edit', compact('category'));
    }


public function update(
    Request $request,
    MembershipCategory $category
) {
    $validated = $request->validate([
        'name' => [
            'required',
            'string',
            'max:255',
            Rule::unique('membership_categories', 'name')
                ->ignore($category->id),
        ],
        'description' => [
            'nullable',
            'string',
            'max:5000',
        ],
        'level' => [
            'nullable',
            'string',
            'max:255',
        ],
        'is_active' => [
            'required',
            'boolean',
        ],
    ]);

    $category->update([
        'name' => $validated['name'],
        'description' => $validated['description'] ?? null,
        'level' => $validated['level'] ?? null,
        'is_active' => $validated['is_active'],
    ]);

    return redirect()
        ->route('categories.index')
        ->with(
            'success',
            'Membership category updated successfully!'
        );
}


    // Preserve existing category records.
    // Deactivation and deletion are disabled.
    public function destroy(MembershipCategory $category)
    {
        return redirect()
            ->route('categories.index')
            ->with(
                'error',
                'Deleting or deactivating membership categories is disabled.'
            );
    }
}
