
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Membership Categories</title>

    <link rel="stylesheet"
          href="{{ asset('css/design.css') }}">
</head>

<body>

<header>
    <h1>Membership Category Management</h1>
    <p>Manage membership categories and fees</p>
</header>

<main class="container members-container">
    <div class="card">

        <h2>Membership Categories</h2>

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

            <a href="{{ route('categories.create') }}"
               class="btn btn-green">
                + Add Category
            </a>
        </div>

        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Category</th>
                        <th>Level</th>
                        <th>Fee</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($categories as $category)
                        <tr>
                            <td>{{ $category->name }}</td>

                            <td>
                                {{ $category->level ?? 'Not specified' }}
                            </td>

                            <td>
                                ₱{{ number_format($category->fee, 2) }}
                            </td>

                            <td>
                                {{ $category->is_active ? 'Active' : 'Inactive' }}
                            </td>

                            <td>
                                <a href="{{ route('categories.edit', $category) }}"
                                   class="btn">
                                    Edit
                                </a>

                                @if($category->is_active)
                                    <form action="{{ route('categories.destroy', $category) }}"
                                          method="POST"
                                          style="display:inline;"
                                          onsubmit="return confirm('Deactivate this category?');">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                                class="btn btn-gray">
                                            Deactivate
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">
                                No membership categories found.
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
