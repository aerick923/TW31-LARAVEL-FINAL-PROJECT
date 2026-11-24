<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class TaskController extends Controller
{
    // Display all tasks and available regular users.
    public function index()
    {
        $tasks = Task::with('users')
            ->latest()
            ->get();

        $users = User::where('role', 'user')
            ->orderBy('name')
            ->get();

        return view('tasks', compact('tasks', 'users'));
    }

    // Create a task and assign it to selected users.
    public function store(Request $request)
    {
        $validated = $request->validate([
            'task_title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'users' => 'required|array|min:1',
            'users.*' => [
                'required',
                'integer',
                'distinct',
                Rule::exists('users', 'id')
                    ->where('role', 'user'),
            ],
        ]);

        DB::transaction(function () use ($validated) {
            $task = Task::create([
                'task_title' => $validated['task_title'],
                'description' => $validated['description'] ?? null,
                'completed' => false,
            ]);

            $task->users()->attach($validated['users']);
        });

        return redirect()
            ->route('tasks.index')
            ->with('success', 'Task created and assigned successfully.');
    }

    // Display the task editing form.
    public function edit(Task $task)
    {

    if ($task->users()->exists()
    && !$task->users()
        ->wherePivot('completed', false)
        ->exists()) {
    return redirect()
        ->route('tasks.index')
        ->with('success', 'Completed tasks cannot be edited.');
}
        $task->load('users');

        $users = User::where('role', 'user')
            ->orderBy('name')
            ->get();

        return view('tasks.edit', compact('task', 'users'));
    }

    // Update a task and its assigned users.
    public function update(Request $request, Task $task)
    {
        $validated = $request->validate([
            'task_title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'users' => 'required|array|min:1',
            'users.*' => [
                'required',
                'integer',
                'distinct',
                Rule::exists('users', 'id')
                    ->where('role', 'user'),
            ],
        ]);

        DB::transaction(function () use ($task, $validated) {

            // Lock the task while updating assignments.
            $task = Task::whereKey($task->id)
                ->lockForUpdate()
                ->firstOrFail();

            // Update task information.
            $task->update([
                'task_title' => $validated['task_title'],
                'description' => $validated['description'] ?? null,
            ]);

            // Preserve existing users' completion records.
            // Add new assignments and remove unselected users.
            $task->users()->sync($validated['users']);

            // Count all assigned users.
            $totalAssigned = $task->users()->count();

            // Count users who completed their assignments.
            $totalCompleted = $task->users()
                ->wherePivot('completed', true)
                ->count();

            // Mark the task completed only when all
            // currently assigned users have finished.
            $task->update([
                'completed' => $totalAssigned > 0
                    && $totalCompleted === $totalAssigned,
            ]);
        });

        return redirect()
            ->route('tasks.index')
            ->with('success', 'Task and assignments updated successfully.');
    }

    // Delete an existing task.
    public function destroy(Task $task)
    {
        $task->delete();

        return redirect()
            ->route('tasks.index')
            ->with('success', 'Task deleted successfully.');
    }
}
