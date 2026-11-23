
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>My Application | MemberHub</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f7f5ff;
            color: #30236b;
        }

        .navbar {
            background: white;
            border-bottom: 1px solid #e6dfff;
            padding: 18px 30px;
        }

        .navbar-inner {
            max-width: 1100px;
            margin: auto;
            display: flex;
            align-items: center;
        }

        .brand {
            font-size: 21px;
            font-weight: bold;
            color: #30236b;
            text-decoration: none;
        }

        .container {
            max-width: 1000px;
            margin: 50px auto;
            padding: 0 20px;
        }

        .page-heading {
            margin-bottom: 30px;
        }

        .page-heading h1 {
            font-size: 32px;
            margin-bottom: 10px;
        }

        .page-heading p {
            color: #756b92;
            line-height: 1.6;
        }

        .card {
            background: white;
            border: 1px solid #e4dcff;
            border-radius: 16px;
            padding: 30px;
            box-shadow: 0 8px 25px rgba(48, 35, 107, 0.05);
        }

        .table-wrapper {
            width: 100%;
            overflow-x: auto;
        }

        .application-table {
            width: 100%;
            border-collapse: collapse;
            min-width: 600px;
        }

        .application-table th {
            background: #ede9fe;
            color: #30236b;
            text-align: left;
            padding: 16px;
            font-size: 13px;
        }

        .application-table td {
            padding: 16px;
            border-bottom: 1px solid #eee8ff;
            font-size: 14px;
            overflow-wrap: anywhere;
        }

        .application-table tbody tr:hover {
            background: #faf8ff;
        }

        .status-badge {
            display: inline-block;
            padding: 7px 13px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
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

        .actions {
            margin-top: 30px;
        }

        .btn {
            display: inline-block;
            background: #8065e8;
            color: white;
            text-decoration: none;
            padding: 13px 20px;
            border-radius: 9px;
            font-weight: bold;
            font-size: 14px;
        }

        .btn:hover {
            background: #684bd3;
        }

        .empty-state {
            background: #f5f3ff;
            color: #756b92;
            padding: 25px;
            border-radius: 10px;
            line-height: 1.6;
        }

        @media (max-width: 650px) {
            .container {
                margin-top: 25px;
            }

            .card {
                padding: 20px;
            }

            .page-heading h1 {
                font-size: 26px;
            }
        }
    </style>
</head>

<body>

<header class="navbar">
    <div class="navbar-inner">
        <a href="{{ route('user.dashboard') }}"
           class="brand">
            MemberHub
        </a>
    </div>
</header>

<main class="container">

    <div class="page-heading">
        <h1>My Application</h1>
        <p>
            View the details and current status of your membership application.
        </p>
    </div>

    <div class="card">

        @if($applications->isEmpty())

            <div class="empty-state">
                	You have not submitted a membership application yet.
            </div>

        @else

            <div class="table-wrapper">
                <table class="application-table">

                    <thead>
                        <tr>
                            <th>Reference No.</th>
                            <th>Membership Category</th>
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
                                    {{ $application->category?->name
                                        ?? 'Unavailable' }}
                                </td>

                                <td>
                                    <span class="status-badge status-{{ strtolower($application->status) }}">
                                        {{ ucfirst($application->status) }}
                                    </span>
                                </td>

                                <td>
                                    {{ $application->created_at
                                        ?->format('F d, Y') ?? 'N/A' }}
                                </td>
                            </tr>

                        @endforeach
                    </tbody>

                </table>
            </div>

        @endif

        <div class="actions">
            <a href="{{ route('user.dashboard') }}"
               class="btn">
                Back to Dashboard
            </a>
        </div>

    </div>

</main>

</body>
</html>
