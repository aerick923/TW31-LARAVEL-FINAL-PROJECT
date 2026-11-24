
@extends('layouts.admin')

@section('title', 'Edit Task')

@section('page-title', 'Edit Task')

@push('styles')
<style>
    .task-edit-container {
        max-width: 1000px;
        margin: 0 auto;
    }

    .task-edit-container .form-group {
        margin-bottom: 22px;
    }

    .task-edit-container input,
    .task-edit-container textarea {
        display: block;
        width: 100%;
        max-width: 100%;
        box-sizing: border-box;
    }

    .task-edit-container textarea {
        min-height: 130px;
        resize: vertical;
    }

    .task-edit-container .toolbar {
        display: flex;
        justify-content: flex-start;
        gap: 10px;
        flex-wrap: wrap;
    }
</style>
@endpush

@section('content')

<div class="task-edit-container">

    <h1>Edit Task</h1>

    <p>Update task information and description.</p>

    <div class="card">

        <h2>Task Information</h2>

        @if($errors->any())
            <div class="error-box" role="alert">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('tasks.update', $task) }}"
              method="POST">

            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="task_title">Task Title</label>

                <input type="text"
                       id="task_title"
                       name="task_title"
                       value="{{ old('task_title', $task->task_title) }}"
                       maxlength="255"
                       required>

                @error('task_title')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="description">Description</label>

                <textarea id="description"
                          name="description"
                          rows="5"
                          placeholder="Describe the task">{{ old('description', $task->description) }}</textarea>

                @error('description')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
    <label>Assign Task to Users</label>

    <p style="color: #665a91; font-size: 13px;">
        Select one or more users who should receive this task.
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
                           old(
                               'users',
                               $task->users->pluck('id')->all()
                           )
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

            <div class="toolbar">
                <button type="submit" class="btn">
                    Update Task
                </button>

                <a href="{{ route('tasks.index') }}"
                   class="btn btn-gray">
                    Cancel
                </a>

            </div>

        </form>

    </div>

</div>

@endsection
