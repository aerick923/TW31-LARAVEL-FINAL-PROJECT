<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

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
    public function complete(Task $task): RedirectResponse
    {
        $user = Auth::user();

        $alreadyCompleted = DB::transaction(function () use ($task, $user) {

            // Lock the task to prevent simultaneous updates.
            $task = Task::whereKey($task->id)
                ->lockForUpdate()
                ->firstOrFail();

            // Only allow users to complete assigned tasks.
            $assignedTask = $user->tasks()
                ->where('tasks.id', $task->id)
                ->firstOrFail();

            // Prevent duplicate completion.
            if ($assignedTask->pivot->completed) {
                return true;
            }

            // Mark this user's assignment as completed.
            $user->tasks()->updateExistingPivot($task->id, [
                'completed' => true,
                'completed_at' => now(),
            ]);

            // Count all users assigned to this task.
            $totalAssigned = $task->users()->count();

            // Count users who completed their assignments.
            $totalCompleted = $task->users()
                ->wherePivot('completed', true)
                ->count();

            // Mark the overall task as completed only
            // when all assigned users have finished.
            $task->update([
                'completed' => $totalAssigned > 0
                    && $totalCompleted === $totalAssigned,
            ]);

            return false;
        });

        if ($alreadyCompleted) {
            return redirect()
                ->route('user.tasks.index')
                ->with(
                    'success',
                    'This task is already completed.'
                );
        }

        return redirect()
            ->route('user.tasks.index')
            ->with(
                'success',
                'Task marked as completed successfully!'
            );
    }
}
