
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Member Management</title>

    <link rel="stylesheet" href="{{ asset('css/design.css') }}">
</head>

<body>

<header>
    <h1>Member Management</h1>
    <p>Manage membership records easily</p>

    <a href="{{ route('tasks.index') }}">
        Back to Task Manager
    </a>
</header>

<main class="container members-container">
    <div class="card">

        <h2>Registered Members</h2>

        @if(session('success'))
            <div class="success">
                {{ session('success') }}
            </div>
        @endif

        <div class="toolbar">

            <form action="{{ route('members.index') }}" method="GET">

                <input type="text"
                       name="search"
                       placeholder="Search name, category, or level"
                       value="{{ request('search') }}">

                <button type="submit" class="btn">
                    Search
                </button>

                <a href="{{ route('members.index') }}"
                   class="btn btn-gray">
                    Clear
                </a>

            </form>

            <a href="{{ route('members.create') }}"
               class="btn btn-green">
                + Add New Member
            </a>

        </div>

        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Membership No.</th>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Level</th>
                        <th>Membership Fee</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($members as $member)
                        <tr>
                            <td>{{ $member->membership_number }}</td>
                            <td>{{ $member->name }}</td>
                            <td>{{ $member->category }}</td>
                            <td>{{ $member->level }}</td>

                            <td>
                                @if($member->membershipLevel)
                                    ₱{{ number_format($member->membershipLevel->fee, 2) }}
                                @else
                                    Fee unavailable
                                @endif
                            </td>

                            <td>{{ $member->status }}</td>

                            <td>
                                <div class="actions">

                                    <a href="{{ route('members.show', $member) }}"
                                       class="btn">
                                        View
                                    </a>

                                    <a href="{{ route('members.edit', $member) }}"
                                       class="btn btn-orange">
                                        Edit
                                    </a>

                                    <form action="{{ route('members.toggleStatus', $member) }}"
                                          method="POST">
                                        @csrf
                                        @method('PATCH')

                                        <button type="submit"
                                                class="btn btn-gray">
                                            {{ $member->status === 'Active'
                                                ? 'Deactivate'
                                                : 'Activate' }}
                                        </button>
                                    </form>

                                    <form action="{{ route('members.destroy', $member) }}"
                                          method="POST"
                                          onsubmit="return confirm('Permanently delete this member?');">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                                class="btn btn-red">
                                            Delete
                                        </button>
                                    </form>

                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="empty-table">
                                No members found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pagination">
            {{ $members->withQueryString()->links() }}
        </div>

    </div>
</main>

</body>
</html>
