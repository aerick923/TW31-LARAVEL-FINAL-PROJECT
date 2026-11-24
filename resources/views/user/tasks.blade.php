
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>My Tasks - MemberHub</title>

    <link rel="stylesheet"
          href="{{ asset('css/design.css') }}">

    <style>
        .user-tasks-container {
            max-width: 950px;
            margin: 45px auto;
            padding: 0 20px;
        }

        .user-task-card {
            background: #ffffff;
            border: 1px solid #ded8ff;
            border-radius: 12px;
            padding: 22px;
            margin-bottom: 18px;
        }

        .user-task-heading {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
            flex-wrap: wrap;
        }

        .user-task-heading h3 {
            color: #30236b;
            margin: 0;
        }

        .user-task-description {
            color: #665a91;
            margin-top: 12px;
            line-height: 1.6;
        }

        .user-task-status {
            display: inline-block;
            padding: 7px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .user-task-status.pending {
            background: #fff0c2;
            color: #946000;
        }

        .user-task-status.completed {
            background: #d7f9e5;
            color: #087a50;
        }

        .user-task-footer {
            margin-top: 18px;
            padding-top: 15px;
            border-top: 1px solid #e5dfff;
            color: #665a91;
            font-size: 13px;
        }

        .user-tasks-empty {
            text-align: center;
            padding: 40px 20px;
            color: #665a91;
        }
    </style>
</head>

<body>

<main class="user-tasks-container">


@if(session('success'))
    <div class="success" role="status"
         style="margin-bottom: 20px;">
        {{ session('success') }}
    </div>
@endif

    <h1>My Assigned Tasks</h1>

    <p>
        View tasks assigned to you by the administrator.
    </p>

    <div class="card">

        <h2>My Tasks ({{ $tasks->count() }})</h2>

        @forelse($tasks as $task)

            <div class="user-task-card">

                <div class="user-task-heading">

                    <h3>{{ $task->task_title }}</h3>

                    @if($task->pivot->completed)

                        <span class="user-task-status completed">
                            Completed
                        </span>

                    @else

                        <span class="user-task-status pending">
                            Pending
                        </span>

                    @endif

                </div>

                <p class="user-task-description">
                    {{ $task->description ?: 'No description provided.' }}
                </p>

                <div class="user-task-footer">

                    @if($task->pivot->completed)

                        Completed on:
                        {{ $task->pivot->completed_at
                            ? \Illuminate\Support\Carbon::parse(
                                $task->pivot->completed_at
                              )->format('M d, Y h:i A')
                            : 'Date unavailable' }}

                    @else

                        Assigned Task - Awaiting Completion


<form action="{{ route('user.tasks.complete', $task) }}"
      method="POST"
      style="margin-top: 15px;">

    @csrf
    @method('PATCH')

    <button type="submit"
            class="btn"
            style="
                background: #8767ef;
                color: white;
                border: none;
                border-radius: 8px;
                padding: 11px 18px;
                cursor: pointer;
                font-weight: 600;
            ">
        Mark as Completed
    </button>

</form>


                    @endif

                </div>

            </div>

        @empty

            <div class="user-tasks-empty">

                <h3>No Tasks Assigned</h3>

                <p>
                    You currently have no assigned tasks.
                </p>

            </div>


        @endforelse

        <!-- Back to Dashboard -->
        <div style="margin-top: 25px;">

            <a href="{{ route('user.dashboard') }}"
               class="btn"
               style="
                   display: inline-block;
                   background-color: #8767ef;
                   color: white;
                   padding: 12px 22px;
                   border-radius: 8px;
                   text-decoration: none;
                   font-weight: 600;
               ">
                Back to Dashboard
            </a>

        </div>

    </div>

</main>


</body>
</html>
