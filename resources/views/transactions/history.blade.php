
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Member Transaction History</title>
    <link rel="stylesheet" href="{{ asset('css/design.css') }}">
</head>
<body>

<header>
    <h1>Transaction History</h1>
    <p>View membership transactions and payment records</p>
</header>

<main class="container members-container">
    <div class="card">

        <h2>{{ $member->name }}</h2>

        <p>
            <strong>Membership Number:</strong>
            {{ $member->membership_number }}
        </p>

        <p>
            <strong>Membership Level:</strong>
            {{ $member->level }}
        </p>

        <div class="toolbar">
            <a href="{{ route('members.show', $member) }}"
               class="btn btn-gray">
                Back to Member
            </a>

            <a href="{{ route('transactions.index') }}"
               class="btn">
                All Transactions
            </a>
        </div>

        <h2>Membership Transactions</h2>

        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Reference No.</th>
                        <th>Type</th>
                        <th>Amount</th>
                        <th>Total Paid</th>
                        <th>Status</th>
                        <th>Start Date</th>
                        <th>Expiration Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($transactions as $transaction)
                        <tr>
                            <td>{{ $transaction->reference_number }}</td>
                            <td>{{ $transaction->transaction_type }}</td>
                            <td>₱{{ number_format($transaction->amount, 2) }}</td>

                            <td>
                                ₱{{ number_format(
                                    $transaction->payments->sum('amount'), 2
                                ) }}
                            </td>

                            <td>{{ $transaction->status }}</td>

                            <td>
                                {{ $transaction->start_date?->format('M d, Y') ?? '—' }}
                            </td>

                            <td>
                                {{ $transaction->end_date?->format('M d, Y') ?? '—' }}
                            </td>

                            <td>
                                <a href="{{ route('transactions.show', $transaction) }}"
                                   class="btn">
                                    View Payments
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8">
                                No transaction history available.
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
