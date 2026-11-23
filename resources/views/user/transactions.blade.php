
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>My Transactions | MemberHub</title>

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
            max-width: 1100px;
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

        .transaction-table {
            width: 100%;
            border-collapse: collapse;
            min-width: 760px;
        }

        .transaction-table th {
            background: #ede9fe;
            color: #30236b;
            padding: 15px;
            text-align: left;
            font-size: 13px;
        }

        .transaction-table td {
            padding: 15px;
            border-bottom: 1px solid #eee8ff;
            font-size: 13px;
        }

        .transaction-table tbody tr:hover {
            background: #faf8ff;
        }

        .badge {
            display: inline-block;
            padding: 7px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }

        .status-approved {
            background: #d1fae5;
            color: #065f46;
        }

        .status-pending {
            background: #fef3c7;
            color: #92400e;
        }

        .status-paid {
            background: #dbeafe;
            color: #1e40af;
        }

        .status-cancelled {
            background: #fee2e2;
            color: #991b1b;
        }

        .btn {
            display: inline-block;
            background: #8065e8;
            color: white;
            text-decoration: none;
            padding: 11px 16px;
            border-radius: 9px;
            font-weight: bold;
            font-size: 13px;
            white-space: nowrap;
        }

        .btn:hover {
            background: #684bd3;
        }

        .btn-secondary {
            background: #ede9fe;
            color: #49358f;
        }

        .btn-secondary:hover {
            background: #ddd6fe;
        }

        .actions {
            margin-top: 30px;
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
        <h1>My Transactions</h1>
        <p>
            View your membership transactions,
            payment history, and printable receipts.
        </p>
    </div>

    <div class="card">

        @if($transactions->isEmpty())

            <div class="empty-state">
                You do not have any membership
                transactions yet.
            </div>

        @else

            <div class="table-wrapper">
                <table class="transaction-table">

                    <thead>
                        <tr>
                            <th>Reference No.</th>
                            <th>Transaction Type</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Total Paid</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($transactions as $transaction)

                            @php
                                $totalPaid = $transaction
                                    ->payments
                                    ->sum('amount');
                            @endphp

                            <tr>
                                <td>
                                    {{ $transaction->reference_number }}
                                </td>

                                <td>
                                    {{ $transaction->transaction_type }}
                                </td>

                                <td>
                                    ₱{{ number_format(
                                        $transaction->amount, 2
                                    ) }}
                                </td>

                                <td>
                                    <span class="badge status-{{ strtolower($transaction->status) }}">
                                        {{ $transaction->status }}
                                    </span>
                                </td>

                                <td>
                                    ₱{{ number_format($totalPaid, 2) }}
                                </td>

                                <td>
                                    {{ $transaction->created_at
                                        ?->format('M d, Y') ?? 'N/A' }}
                                </td>

                                <td>
                                    @if($transaction->payments->isNotEmpty())

                                        <a
                                            href="{{ route(
                                                'user.transactions.receipt',
                                                $transaction
                                            ) }}"
                                            class="btn btn-secondary"
                                        >
                                            View Receipt
                                        </a>

                                    @else

                                        <span style="color: #756b92;">
                                            No payment yet
                                        </span>

                                    @endif
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
