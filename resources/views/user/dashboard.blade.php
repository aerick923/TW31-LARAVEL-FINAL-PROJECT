
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>User Dashboard</title>

    <link rel="stylesheet"
          href="{{ asset('css/design.css') }}">

    <style>
        .application-table-wrapper {
            width: 100%;
            overflow-x: auto;
            margin-top: 20px;
        }

        .application-table {
            width: 100%;
            border-collapse: collapse;
        }

        .application-table th,
        .application-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd6fe;
        }

        .application-table th {
            background: #ede9fe;
            color: #30236b;
        }

        .application-table td {
            overflow-wrap: anywhere;
        }

        .application-table tr:hover {
            background: #faf8ff;
        }

        .status-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            background: #f3f4f6;
            color: #374151;
        }


.status-pending {
    background: #fef3c7;
    color: #92400e;
}

.status-approved {
    background: #d1fae5;
    color: #065f46;
}

.status-rejected {
    background: #fee2e2;
    color: #991b1b;
}


        .membership-actions {
            margin-top: 20px;
        }

        .membership-actions .btn {
            display: inline-block;
            text-decoration: none;
        }

        .empty-applications {
            padding: 20px;
            background: #f5f3ff;
            border-radius: 8px;
            margin-top: 15px;
        }
    </style>
</head>

<body>

<header class="hero">
    <h1>My Membership Dashboard</h1>
    <p>Laravel Membership Management System</p>
</header>

<main class="container">

    <div class="card"
         style="max-width: 900px; margin: 40px auto;">

        <h2>Welcome, {{ auth()->user()->name }}!</h2>

        @if (session('success'))
            <div style="
                background: #d1fae5;
                color: #065f46;
                padding: 15px;
                border-radius: 8px;
                margin: 20px 0;
            " role="status">
                {{ session('success') }}
            </div>
        @endif

        <h3>My Account Information</h3>

        <p>
            <strong>Name:</strong>
            {{ auth()->user()->name }}
        </p>

        <p>
            <strong>Email:</strong>
            {{ auth()->user()->email }}
        </p>

        <p>
            <strong>Email Verification:</strong>
            {{ auth()->user()->hasVerifiedEmail()
                ? 'Verified'
                : 'Not Verified' }}
        </p>

        <p>
            <strong>Account Type:</strong>
            Regular User
        </p>

        <hr style="margin: 25px 0;">

        <!-- MEMBERSHIP APPLICATION HISTORY -->

        <h3>My Membership Applications</h3>

        <p>
            View your submitted membership applications
            and their current status.
        </p>

        @if($applications->isEmpty())

            <div class="empty-applications">
                <p>
                    You have not submitted any membership
                    applications yet.
                </p>
            </div>

        @else

            <div class="application-table-wrapper">

                <table class="application-table">

                    <thead>
                        <tr>
                            <th>Reference No.</th>
                            <th>Category</th>
                            <th>Status</th>
                            <th>Date Submitted</th>
                        </tr>
                    </thead>

                    <tbody>

                        @foreach($applications as $application)

                            <tr>
                                <td>
                                    {{ $application->reference_no }}
                                </td>

                                <td>
                                    {{ $application->category?->name ?? 'Unavailable' }}
                                </td>

                                <td>
                                    
<span class="status-badge status-{{ strtolower($application->status) }}">
    {{ ucfirst($application->status) }}
</span>

                                </td>

                                <td>
                                    {{ $application->created_at?->format('M d, Y') ?? 'N/A' }}
                                </td>
                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

        @endif

        <!-- MEMBERSHIP APPLICATION BUTTON -->

        <div class="membership-actions">

            <a href="{{ route('membership.create') }}"
               class="btn">
                Apply for Membership
            </a>

        </div>

        <hr style="margin: 25px 0;">

        <!-- LOGOUT -->

        <form action="{{ route('logout') }}"
              method="POST"
              style="margin-top: 25px;">

            @csrf

            <button type="submit" class="btn">
                Logout
            </button>

        </form>

    </div>

</main>

</body>
</html>
