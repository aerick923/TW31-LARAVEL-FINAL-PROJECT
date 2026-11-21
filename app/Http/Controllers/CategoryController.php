<?php

namespace App\Http\Controllers;

use App\Models\MembershipCategory;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    // Display all membership categories
    public function index()
    {
        $categories = MembershipCategory::orderBy('name')->get();

        return view('categories.index', compact('categories'));
    }

    // Display the category creation form
    public function create()
    {
        return view('categories.create');
    }

    // Save a new membership category
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('membership_categories', 'name'),
            ],
            'level' => ['nullable', 'string', 'max:255'],
            'fee' => ['required', 'numeric', 'min:0', 'max:99999999.99'],
            'is_active' => ['required', 'boolean'],
        ]);

        $category = new MembershipCategory();
        $category->name = $validated['name'];
        $category->level = $validated['level'] ?? null;
        $category->fee = $validated['fee'];
        $category->is_active = $validated['is_active'];
        $category->save();

        return redirect()
            ->route('categories.index')
            ->with('success', 'Membership category created successfully!');
    }

    // Display the category edit form
    public function edit(MembershipCategory $category)
    {
        return view('categories.edit', compact('category'));
    }

    // Update a membership category
    public function update(Request $request, MembershipCategory $category)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('membership_categories', 'name')
                    ->ignore($category->id),
            ],
            'level' => ['nullable', 'string', 'max:255'],
            'fee' => ['required', 'numeric', 'min:0', 'max:99999999.99'],
            'is_active' => ['required', 'boolean'],
        ]);

        $category->name = $validated['name'];
        $category->level = $validated['level'] ?? null;
        $category->fee = $validated['fee'];
        $category->is_active = $validated['is_active'];
        $category->save();

        return redirect()
            ->route('categories.index')
            ->with('success', 'Membership category updated successfully!');
    }

    // Deactivate a category instead of deleting records
    public function destroy(MembershipCategory $category)
    {
        $category->is_active = false;
        $category->save();

        return redirect()
            ->route('categories.index')
            ->with('success', 'Membership category deactivated successfully!');
    }
}
