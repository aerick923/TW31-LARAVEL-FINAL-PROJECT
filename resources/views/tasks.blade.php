
@extends('layouts.admin')

@section('title', 'Task Management')
@section('page-title', 'Task Management')

@push('styles')
<style>
    .task-management-container {
        max-width: 1000px;
        margin: 0 auto;
    }

    .task-management-container .page-heading {
        margin-bottom: 25px;
    }

    .task-management-container .page-heading h1 {
        color: #30236b;
        margin-bottom: 8px;
    }

    .task-management-container .page-heading p {
        color: #665a91;
    }

    .task-management-container .card {
        margin-bottom: 24px;
        padding: 26px;
    }

    .task-management-container .form-group {
        margin-bottom: 20px;
    }

    .task-management-container input,
    .task-management-container textarea {
        display: block;
        width: 100%;
        max-width: 100%;
        box-sizing: border-box;
    }

    .task-management-container textarea {
        min-height: 110px;
        resize: vertical;
    }

    .task-management-container .task-list {
        display: flex;
        flex-direction: column;
        gap: 15px;
        margin-top: 20px;
    }

    .task-management-container .task-item {
        padding: 20px;
        background: #faf9ff;
        border: 1px solid #e5dfff;
        border-radius: 10px;
    }

    .task-management-container .task-item-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
        margin-bottom: 12px;
    }

    .task-management-container .task-item h3 {
        color: #30236b;
        margin: 0;
        font-size: 18px;
    }

    .task-management-container .task-description {
        color: #665a91;
        margin-bottom: 15px;
        overflow-wrap: anywhere;
    }

    .task-management-container .task-status {
        padding: 7px 12px;
        border-radius: 20px;
        background: #fff0c2;
        color: #8a5700;
        font-size: 12px;
        font-weight: 600;
    }

    .task-management-container .task-status.completed {
        background: #d5fae6;
        color: #086b46;
    }

    .task-management-container .task-actions {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }

    .task-management-container .task-actions form {
        margin: 0;
    }

    .task-management-container .task-empty {
        text-align: center;
        padding: 35px;
        color: #665a91;
    }
</style>
@endpush

@section('content')

<div class="task-management-container">

    <div class="page-heading">
        <h1>Task Management</h1>
        <p>Create, manage, and assign tasks to registered users.</p>
    </div>

    @if(session('success'))
        <div class="success" role="status">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="error-box" role="alert">
            <strong>Please correct the following errors:</strong>
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- CREATE TASK -->
    <div class="card">

        <h2>Create New Task</h2>

        <form action="{{ route('tasks.store') }}"
              method="POST">

            @csrf

            <div class="form-group">
                <label for="task_title">Task Title</label>

                <input type="text"
                       id="task_title"
                       name="task_title"
                       value="{{ old('task_title') }}"
                       maxlength="255"
                       placeholder="Enter task title"
                       required>
            </div>

            <div class="form-group">
                <label for="description">Description</label>

                <textarea id="description"
                          name="description"
                          rows="4"
                          placeholder="Describe the task">{{ old('description') }}</textarea>
            </div>

<div class="form-group">
    <label>Assign Task to Users</label>

    <p style="color: #665a91; font-size: 13px;">
        Select one or more registered users.
    </p>

    <div style="
        border: 1px solid #e5dfff;
        border-radius: 10px;
        padding: 15px;
        background: #faf9ff;
        max-height: 220px;
        overflow-y: auto;
    ">

        @forelse($users as $user)

            <label style="
                display: flex;
                align-items: center;
                gap: 12px;
                padding: 10px;
                cursor: pointer;
            ">

                <input type="checkbox"
                       name="users[]"
                       value="{{ $user->id }}"
                       @checked(in_array(
                           $user->id,
                           old('users', [])
                       ))
                       style="
                           width: 18px;
                           height: 18px;
                           flex-shrink: 0;
                       ">

                <span>
                    {{ $user->name }}
                    <small style="color: #665a91;">
                        ({{ $user->email }})
                    </small>
                </span>

            </label>

        @empty

            <p style="color: #665a91;">
                No registered users available for assignment.
            </p>

        @endforelse

    </div>

    @error('users')
        <p style="color: #b91c1c;">
            {{ $message }}
        </p>
    @enderror

    @error('users.*')
        <p style="color: #b91c1c;">
            {{ $message }}
        </p>
    @enderror

</div>

            <button type="submit" class="btn">
                + Create Task
            </button>

        </form>

    </div>

    <!-- EXISTING TASKS -->
    <div class="card">

        <h2>Existing Tasks ({{ $tasks->count() }})</h2>

        <div class="task-list">

            @forelse($tasks as $task)

                <div class="task-item">

                    <div class="task-item-header">

                        <h3>{{ $task->task_title }}</h3>



@php
    $totalAssigned = $task->users->count();

    $totalCompleted = $task->users
        ->filter(fn ($user) => (bool) $user->pivot->completed)
        ->count();

    $taskStatus = $totalAssigned > 0
        && $totalCompleted === $totalAssigned
            ? 'Completed'
            : 'In Progress';
@endphp

<span class="task-status {{ $taskStatus === 'Completed' ? 'completed' : '' }}">
    {{ $taskStatus }}
</span>


                    </div>

                    <p class="task-description">
                        {{ $task->description ?: 'No description provided.' }}
                    </p>


<div class="task-assignees"
     style="margin: 15px 0;">

    <strong style="color: #30236b;">
        Assigned Users ({{ $task->users->count() }})
    </strong>

    <div style="
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-top: 10px;
    ">

        @forelse($task->users as $user)

            <span style="
                display: inline-flex;
                align-items: center;
                gap: 6px;
                padding: 8px 12px;
                border-radius: 8px;
                background: #f0ecff;
                color: #30236b;
                font-size: 12px;
            ">

                {{ $user->name }}

                @if($user->pivot->completed)
                    <strong style="color: #087a50;">
                        ✓ Completed
                    </strong>
                @else
                    <strong style="color: #a66b00;">
                        • Pending
                    </strong>
                @endif

            </span>

        @empty

            <span style="
                color: #665a91;
                font-size: 13px;
            ">
                No users assigned yet.
            </span>

        @endforelse

    </div>

</div>


                    <div class="task-actions">


@if($taskStatus !== 'Completed')
    <a href="{{ route('tasks.edit', $task) }}"
       class="btn">
        Edit
    </a>
@endif


                        <form action="{{ route('tasks.destroy', $task) }}"
                              method="POST"
                              onsubmit="return confirm('Delete this task?');">

                            @csrf
                            @method('DELETE')

                            <button type="submit" class="btn btn-red">
                                Delete
                            </button>

                        </form>

                    </div>

                </div>

            @empty

                <div class="task-empty">
                    <h3>No tasks available</h3>
                    <p>Create your first task using the form above.</p>
                </div>

            @endforelse

        </div>

    </div>

</div>

@endsection
