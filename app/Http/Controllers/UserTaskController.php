<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Models\Task;

class UserTaskController extends Controller
{
    // Display tasks assigned to the logged-in user.
    public function index(): View
    {
        $user = Auth::user();

        $tasks = $user->tasks()
            ->orderByDesc('tasks.created_at')
            ->get();

        return view('user.tasks', compact('tasks'));
    }


    // Mark an assigned task as completed.
    public function complete(Task $task)
    {
        $user = auth()->user();

        // Only allow users to complete tasks assigned to them.
        $assignedTask = $user->tasks()
            ->where('tasks.id', $task->id)
            ->firstOrFail();

        // Prevent completing the same task twice.
        if ($assignedTask->pivot->completed) {
            return redirect()
                ->route('user.tasks.index')
                ->with('success', 'This task is already completed.');
        }

        // Update only this user's task assignment.
        $user->tasks()->updateExistingPivot($task->id, [
            'completed' => true,
            'completed_at' => now(),
        ]);

        return redirect()
            ->route('user.tasks.index')
            ->with('success', 'Task marked as completed successfully!');
    }

}
