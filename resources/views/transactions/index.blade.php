
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Transaction Management</title>

    <link rel="stylesheet"
          href="{{ asset('css/design.css') }}">
</head>

<body>

<header>
    <h1>Transaction Management</h1>
    <p>Manage membership applications and renewals</p>
</header>

<main class="container members-container">
    <div class="card">

        <h2>Membership Transactions</h2>

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


<form action="{{ route('logout') }}" method="POST"
      style="display: inline;">
    @csrf

    <button type="submit" class="btn btn-gray">
        Logout
    </button>
</form>

            <a href="{{ route('members.index') }}"
               class="btn btn-gray">
                Back to Members
            </a>

            <a href="{{ route('transactions.create') }}"
               class="btn btn-green">
                + New Transaction
            </a>
        </div>

        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Reference No.</th>
                        <th>Member</th>
                        <th>Transaction Type</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($transactions as $transaction)
                        <tr>
                            <td>
                                {{ $transaction->reference_number }}
                            </td>

                            <td>
                                {{ $transaction->member->name }}
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
                                {{ $transaction->created_at->format('M d, Y') }}
                            </td>

                            <td>
                                <div class="actions">
                                    <a href="{{ route('transactions.show', $transaction) }}"
                                       class="btn">
                                        View
                                    </a>

                                    <a href="{{ route('transactions.history', $transaction->member) }}"
                                       class="btn btn-gray">
                                        History
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="empty-table-message">
                                No membership transactions found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pagination">
            {{ $transactions->links() }}
        </div>

    </div>
</main>

</body>
</html>
