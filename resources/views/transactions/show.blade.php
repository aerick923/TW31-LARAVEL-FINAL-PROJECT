
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaction Details</title>

    <link rel="stylesheet" href="{{ asset('css/design.css') }}">
</head>

<body>

<header>
    <h1>Transaction Details</h1>
    <p>View membership transaction and payment information</p>
</header>

<main class="container members-container">
    <div class="card">

        <h2>Transaction Information</h2>

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

        <div class="detail">
            <strong>Transaction Reference</strong>
            <span>{{ $transaction->reference_number }}</span>
        </div>

        <div class="detail">
            <strong>Member Name</strong>
            <span>{{ $transaction->member->name }}</span>
        </div>

        <div class="detail">
            <strong>Membership Number</strong>
            <span>{{ $transaction->member->membership_number }}</span>
        </div>

        <div class="detail">
            <strong>Membership Level</strong>
            <span>{{ $transaction->member->level }}</span>
        </div>

        <div class="detail">
            <strong>Transaction Type</strong>
            <span>{{ $transaction->transaction_type }}</span>
        </div>

        <div class="detail">
            <strong>Transaction Status</strong>
            <span>{{ $transaction->status }}</span>
        </div>

        <div class="detail">
            <strong>Membership Fee</strong>
            <span>₱{{ number_format($transaction->amount, 2) }}</span>
        </div>

        <div class="detail">
            <strong>Total Paid</strong>
            <span>₱{{ number_format($totalPaid, 2) }}</span>
        </div>

        <div class="detail">
            <strong>Remaining Balance</strong>
            <span>₱{{ number_format($balance, 2) }}</span>
        </div>

        <div class="detail">
            <strong>Membership Start Date</strong>
            <span>
                {{ $transaction->start_date?->format('F d, Y') ?? 'Not yet approved' }}
            </span>
        </div>

        <div class="detail">
            <strong>Membership Expiration Date</strong>
            <span>
                {{ $transaction->end_date?->format('F d, Y') ?? 'Not yet approved' }}
            </span>
        </div>

        <h2>Payment History</h2>

        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Payment Reference</th>
                        <th>Amount</th>
                        <th>Payment Method</th>
                        <th>Payment Date</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($transaction->payments as $payment)
                        <tr>
                            <td>{{ $payment->payment_reference }}</td>
                            <td>₱{{ number_format($payment->amount, 2) }}</td>
                            <td>{{ $payment->payment_method }}</td>
                            <td>{{ $payment->payment_date->format('F d, Y') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4">
                                No payments recorded for this transaction.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="toolbar">


@if($transaction->status === 'Pending' && $balance > 0)
    <a href="{{ route('payments.create', $transaction) }}"
       class="btn btn-green">
        + Record Payment
    </a>
@endif


            @if($transaction->status === 'Paid')
                <form action="{{ route('transactions.approve', $transaction) }}"
                      method="POST">
                    @csrf
                    @method('PATCH')

                    <button type="submit" class="btn btn-green"
                            onclick="return confirm('Approve this transaction?')">
                        Approve Transaction
                    </button>
                </form>
            @endif

            @if($transaction->status === 'Pending')
                <form action="{{ route('transactions.cancel', $transaction) }}"
                      method="POST">
                    @csrf
                    @method('PATCH')

                    <button type="submit" class="btn btn-red"
                            onclick="return confirm('Cancel this transaction?')">
                        Cancel Transaction
                    </button>
                </form>
            @endif

        </div>


<a href="{{ route('transactions.receipt', $transaction) }}"
   class="btn"
   target="_blank"
   rel="noopener">
    Print Receipt
</a>


        <a href="{{ route('transactions.index') }}"
           class="btn btn-gray">
            Back to Transactions
        </a>

    </div>
</main>

</body>
</html>