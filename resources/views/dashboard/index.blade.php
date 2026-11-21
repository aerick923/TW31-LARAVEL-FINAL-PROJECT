
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Administrator Dashboard</title>

    <link rel="stylesheet"
          href="{{ asset('css/design.css') }}">

    <style>
        .dashboard-grid {
            display: grid;
            grid-template-columns:
                repeat(auto-fit, minmax(210px, 1fr));
            gap: 20px;
            margin: 25px 0;
        }

        .dashboard-stat {
            background: #f5f2ff;
            border: 1px solid #dcd3ff;
            border-radius: 12px;
            padding: 24px;
        }

        .dashboard-stat h3 {
            font-size: 15px;
            color: #50429a;
            margin-bottom: 12px;
        }

        .dashboard-stat .stat-value {
            font-size: 30px;
            font-weight: 700;
            color: #28245e;
        }

        .dashboard-links {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin: 20px 0 30px;
        }

        .dashboard-section {
            margin-top: 30px;
        }

        .dashboard-section h2 {
            margin-bottom: 15px;
        }
    </style>
</head>

<body>

<header>
    <h1>Administrator Dashboard</h1>
    <p>Laravel Membership Management System</p>
</header>

<main class="container members-container">
    <div class="card">

        <h2>Dashboard Overview</h2>

        <p>
            Welcome,
            {{ auth()->user()->name }}!
        </p>

        <div class="dashboard-grid">

            <div class="dashboard-stat">
                <h3>Total Registered Members</h3>

                <div class="stat-value">
                    {{ number_format($totalMembers) }}
                </div>
            </div>

            <div class="dashboard-stat">
                <h3>Active Members</h3>

                <div class="stat-value">
                    {{ number_format($activeMembers) }}
                </div>
            </div>

            <div class="dashboard-stat">
                <h3>Transactions Awaiting Processing</h3>

                <div class="stat-value">
                    {{ number_format($pendingTransactions) }}
                </div>
            </div>

            <div class="dashboard-stat">
                <h3>Total Payments Collected</h3>

                <div class="stat-value">
                    ₱{{ number_format($totalPayments, 2) }}
                </div>
            </div>

        </div>

        <div class="dashboard-section">
            <h2>Quick Navigation</h2>

            <div class="dashboard-links">

                <a href="{{ route('members.index') }}"
                   class="btn">
                    Manage Members
                </a>

                <a href="{{ route('categories.index') }}"
                   class="btn">
                    Manage Categories
                </a>

                <a href="{{ route('levels.index') }}"
                   class="btn">
                    Manage Levels
                </a>

                <a href="{{ route('transactions.index') }}"
                   class="btn">
                    Manage Transactions
                </a>

            </div>
        </div>

        <div class="dashboard-section">
            <h2>Recent Membership Transactions</h2>

            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>Reference No.</th>
                            <th>Member</th>
                            <th>Type</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($recentTransactions as $transaction)
                            <tr>
                                <td>
                                    {{ $transaction->reference_number }}
                                </td>

                                <td>
                                    {{ $transaction->member?->name ?? 'Unknown' }}
                                </td>

                                <td>
                                    {{ $transaction->transaction_type }}
                                </td>

                                <td>
                                    ₱{{ number_format($transaction->amount, 2) }}
                                </td>

                                <td>
                                    {{ $transaction->status }}
                                </td>

                                <td>
                                    <a href="{{ route('transactions.show', $transaction) }}"
                                       class="btn">
                                        View
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6">
                                    No membership transactions found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="dashboard-section">
            <form action="{{ route('logout') }}"
                  method="POST">
                @csrf

                <button type="submit" class="btn btn-gray">
                    Logout
                </button>
            </form>
        </div>

    </div>
</main>

</body>
</html>
