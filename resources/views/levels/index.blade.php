
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Membership Levels</title>

    <link rel="stylesheet"
          href="{{ asset('css/design.css') }}">
</head>

<body>

<header>
    <h1>Membership Level Management</h1>
    <p>Manage membership fees, validity periods, and benefits</p>
</header>

<main class="container members-container">
    <div class="card">

        <h2>Membership Levels</h2>

        @if(session('success'))
            <div class="success" role="status">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="error-box" role="alert">
                {{ session('error') }}
            </div>
        @endif

        <div class="toolbar">
            <a href="{{ route('members.index') }}"
               class="btn btn-gray">
                Back to Members
            </a>

            <a href="{{ route('levels.create') }}"
               class="btn btn-green">
                + Add Level
            </a>
        </div>

        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Level</th>
                        <th>Fee</th>
                        <th>Validity</th>
                        <th>Benefits</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($levels as $level)
                        <tr>
                            <td>{{ $level->name }}</td>

                            <td>
                                ₱{{ number_format($level->fee, 2) }}
                            </td>

                            <td>
                                {{ $level->validity_months ?? 'Not set' }}
                                months
                            </td>

                            <td>
                                {{ $level->benefits ?? 'No benefits specified' }}
                            </td>

                            <td>
                                <a href="{{ route('levels.edit', $level) }}"
                                   class="btn">
                                    Edit
                                </a>

                                <form action="{{ route('levels.destroy', $level) }}"
                                      method="POST"
                                      style="display:inline;"
                                      onsubmit="return confirm('Delete this membership level?');">

                                    @csrf
                                    @method('DELETE')

                                    <button type="submit"
                                            class="btn btn-gray">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">
                                No membership levels found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</main>

</body>
</html>
