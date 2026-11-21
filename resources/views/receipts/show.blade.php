
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Transaction Receipt</title>

    <link rel="stylesheet"
          href="{{ asset('css/design.css') }}">

    <style>
        .receipt-container {
            max-width: 850px;
            margin: 30px auto;
        }

        .receipt-heading {
            text-align: center;
            margin-bottom: 25px;
        }

        .receipt-heading h2 {
            margin-bottom: 8px;
        }

        .receipt-section {
            margin: 25px 0;
        }

        .receipt-section h3 {
            margin-bottom: 15px;
            color: #302866;
        }

        .receipt-row {
            display: flex;
            justify-content: space-between;
            gap: 20px;
            padding: 10px 0;
            border-bottom: 1px solid #e5e0f5;
        }

        .receipt-row strong {
            color: #50429a;
        }

        .receipt-total {
            font-size: 18px;
            font-weight: bold;
        }

        .receipt-actions {
            display: flex;
            gap: 12px;
            margin-top: 25px;
        }

        @media print {
            @page {
                size: A4;
                margin: 15mm;
            }

            body {
                background: white !important;
                color: black;
            }

            header,
            .receipt-actions {
                display: none !important;
            }

            .receipt-container {
                max-width: 100%;
                margin: 0;
            }

            .card {
                border: none !important;
                box-shadow: none !important;
                padding: 0 !important;
            }

            .receipt-section {
                break-inside: avoid;
            }

            .receipt-row strong {
                color: black;
            }
        }
    </style>
</head>

<body>

<header>
    <h1>Membership Transaction Receipt</h1>
    <p>Laravel Membership Management System</p>
</header>

<main class="container receipt-container">
    <div class="card">

        <div class="receipt-heading">
            <h2>Membership Payment Receipt</h2>
            <p>Laravel Membership Management System</p>
            <p>
                Generated on:
                {{ now()->format('F d, Y h:i A') }}
            </p>
        </div>

        <hr>

        <div class="receipt-section">
            <h3>Transaction Information</h3>

            <div class="receipt-row">
                <strong>Transaction Reference</strong>
                <span>{{ $transaction->reference_number }}</span>
            </div>

            <div class="receipt-row">
                <strong>Member Name</strong>
                <span>{{ $transaction->member?->name ?? 'Unknown' }}</span>
            </div>

            <div class="receipt-row">
                <strong>Membership Number</strong>
                <span>
                    {{ $transaction->member?->membership_number ?? 'N/A' }}
                </span>
            </div>

            <div class="receipt-row">
                <strong>Membership Level</strong>
                <span>{{ $transaction->member?->level ?? 'N/A' }}</span>
            </div>

            <div class="receipt-row">
                <strong>Transaction Type</strong>
                <span>{{ $transaction->transaction_type }}</span>
            </div>

            <div class="receipt-row">
                <strong>Transaction Status</strong>
                <span>{{ $transaction->status }}</span>
            </div>
        </div>

        <div class="receipt-section">
            <h3>Payment Summary</h3>

            <div class="receipt-row">
                <strong>Membership Fee</strong>
                <span>
                    ₱{{ number_format($transaction->amount, 2) }}
                </span>
            </div>

            <div class="receipt-row">
                <strong>Total Paid</strong>
                <span>
                    ₱{{ number_format($totalPaid, 2) }}
                </span>
            </div>

            <div class="receipt-row receipt-total">
                <strong>Remaining Balance</strong>
                <span>
                    ₱{{ number_format($balance, 2) }}
                </span>
            </div>
        </div>

        <div class="receipt-section">
            <h3>Payment History</h3>

            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>Payment Reference</th>
                            <th>Amount</th>
                            <th>Method</th>
                            <th>Date</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($transaction->payments as $payment)
                            <tr>
                                <td>{{ $payment->reference_number }}</td>

                                <td>
                                    ₱{{ number_format($payment->amount, 2) }}
                                </td>

                                <td>{{ $payment->payment_method }}</td>

                                <td>
                                    {{ \Illuminate\Support\Carbon::parse(
                                        $payment->payment_date
                                    )->format('M d, Y') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4">
                                    No payments recorded.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="receipt-section">
            <p>
                <strong>Note:</strong>
                This receipt summarizes recorded membership payments.
                It does not replace an official invoice or tax receipt.
            </p>
        </div>

        <div class="receipt-actions">
            <button type="button"
                    class="btn"
                    onclick="window.print()">
                Print Receipt
            </button>

            <a href="{{ route('transactions.show', $transaction) }}"
               class="btn btn-gray">
                Back to Transaction
            </a>
        </div>

    </div>
</main>

</body>
</html>
