
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Task Manager</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <!-- Shared Anime Theme -->
    <link rel="stylesheet" href="{{ asset('css/design.css') }}">
</head>

<body class="task-page">

    <header class="task-header">
        <h1>Task Manager</h1>
        <p>Manage your daily tasks easily</p>

        <a href="{{ route('members.index') }}"
           class="task-navigation">
            Go to Member Management
        </a>
    </header>

    <main class="container task-container">

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <!-- Add Task Form -->
        <section class="card p-4 mb-4">
            <h4 class="mb-3">Add New Task</h4>

            <form action="{{ route('tasks.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="task_title" class="form-label">
                        Task Title
                    </label>

                    <input type="text"
                           class="form-control"
                           id="task_title"
                           name="task_title"
                           value="{{ old('task_title') }}"
                           required>

                    @error('task_title')
                        <p class="text-danger">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">
                        Description
                    </label>

                    <textarea class="form-control"
                              id="description"
                              name="description"
                              rows="3">{{ old('description') }}</textarea>

                    @error('description')
                        <p class="text-danger">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">
                    Add Task
                </button>
            </form>
        </section>

        <!-- Anime Quest Journal -->
        <section class="card p-4 mb-4 quest-journal">

            <div class="quest-heading">
                <div>
                    <h4 class="mb-1">✦ Quest Journal</h4>

                    <p class="quest-subtitle">
                        Track your adventures and accomplishments
                    </p>
                </div>

                <span class="quest-counter">
                    {{ $tasks->count() }} Quests
                </span>
            </div>

            <div class="quest-list">

                @forelse ($tasks as $task)

                    <article class="quest-card">

                        <div class="quest-card-header">
                            <h3>{{ $task->task_title }}</h3>

                            @if ($task->completed)
                                <span class="quest-status completed">
                                    ✓ Completed
                                </span>
                            @else
                                <span class="quest-status">
                                    ✦ In Progress
                                </span>
                            @endif
                        </div>

                        <p class="quest-description">
                            {{ $task->description ?: 'No description provided.' }}
                        </p>

                        <div class="quest-footer">
                            <span class="quest-label">
                                ✧ {{ $task->completed
                                    ? 'Quest Cleared'
                                    : 'Active Quest' }}
                            </span>
                        </div>

                    </article>

                @empty

                    <div class="quest-empty">
                        <h3>✧ No Quests Yet</h3>
                        <p>
                            Your adventure begins with your first task!
                        </p>
                    </div>

                @endforelse

            </div>

        </section>

    </main>

</body>
</html>
