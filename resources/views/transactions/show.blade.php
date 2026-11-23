
@extends('layouts.admin')

@section('title', 'Transaction Details')

@section('page-title', 'Transaction Details')

@section('content')

<style>
    .transaction-details-page {
        width: 100%;
        max-width: 1150px;
        margin: 0 auto;
        padding: 32px 24px 50px;
    }

    .transaction-details-heading {
        margin-bottom: 24px;
    }

    .transaction-details-heading h1 {
        font-size: 26px;
        font-weight: 700;
        color: #28245e;
        margin: 0 0 8px;
    }

    .transaction-details-heading p {
        color: #756b9e;
        font-size: 14px;
        margin: 0;
    }

    .transaction-details-card {
        background: #ffffff;
        border: 1px solid #ddd6fe;
        border-radius: 14px;
        padding: 30px;
        box-shadow: 0 8px 24px rgba(48, 35, 107, 0.06);
    }

    .transaction-details-card h2 {
        font-size: 21px;
        color: #30236b;
        margin: 0 0 22px;
    }

    .transaction-details-section {
        margin-bottom: 30px;
    }

    .transaction-details-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 15px;
    }

    .transaction-detail-item {
        padding: 16px 18px;
        background: #f8f7ff;
        border: 1px solid #e8e2ff;
        border-radius: 10px;
        min-width: 0;
    }

    .transaction-detail-item strong {
        display: block;
        color: #6253a5;
        font-size: 12px;
        font-weight: 600;
        margin-bottom: 9px;
    }

    .transaction-detail-item span {
        display: block;
        color: #28245e;
        font-size: 14px;
        font-weight: 600;
        overflow-wrap: anywhere;
    }

    .transaction-detail-item .transaction-status-badge {
        display: inline-block;
        width: auto;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }

    .transaction-status-pending {
        background: #fef3c7;
        color: #92400e !important;
    }

    .transaction-status-paid {
        background: #dbeafe;
        color: #1d4ed8 !important;
    }

    .transaction-status-approved {
        background: #d1fae5;
        color: #065f46 !important;
    }

    .transaction-status-cancelled {
        background: #fee2e2;
        color: #991b1b !important;
    }

    .transaction-financial-summary {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 15px;
        margin-top: 20px;
    }

    .transaction-financial-item {
        background: #f7f5ff;
        border: 1px solid #e4ddff;
        border-radius: 10px;
        padding: 20px;
    }

    .transaction-financial-item strong {
        display: block;
        font-size: 12px;
        color: #6253a5;
        margin-bottom: 12px;
    }

    .transaction-financial-item span {
        display: block;
        font-size: 23px;
        font-weight: 700;
        color: #30236b;
    }

    .transaction-financial-item.balance {
        background: #fffbeb;
        border-color: #fde68a;
    }

    .transaction-financial-item.balance span {
        color: #92400e;
    }

    .transaction-section-divider {
        border: none;
        border-top: 1px solid #e8e2ff;
        margin: 30px 0;
    }

    .transaction-payment-table-wrapper {
        overflow-x: auto;
        border: 1px solid #7770b8;
        border-radius: 10px;
    }

    .transaction-payment-table {
        width: 100%;
        min-width: 650px;
        border-collapse: collapse;
        font-size: 13px;
    }

    .transaction-payment-table thead {
        background: #eee8ff;
    }

    .transaction-payment-table th {
        color: #30236b;
        font-weight: 700;
        padding: 15px 13px;
        text-align: left;
    }

    .transaction-payment-table td {
        color: #28245e;
        padding: 15px 13px;
        border-top: 1px solid #e8e2f8;
    }

    .transaction-payment-table tbody tr:hover {
        background: #faf8ff;
    }

    .transaction-payment-empty {
        text-align: center;
        padding: 25px !important;
        color: #756b9e !important;
    }

    .transaction-details-actions {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 10px;
        margin-top: 28px;
    }

    .transaction-details-actions form {
        margin: 0;
    }

    .transaction-details-page .transaction-action-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 11px 16px;
        border: none;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
        cursor: pointer;
        white-space: nowrap;
        transition: background 0.2s ease;
    }

    .transaction-details-page .transaction-btn-primary {
        background: #8768ef;
        color: #ffffff;
    }

    .transaction-details-page .transaction-btn-primary:hover {
        background: #7053d6;
    }

    .transaction-details-page .transaction-btn-secondary {
        background: #495879;
        color: #ffffff;
    }

    .transaction-details-page .transaction-btn-secondary:hover {
        background: #354361;
    }

    .transaction-details-page .transaction-btn-green {
        background: #2e8b68;
        color: #ffffff;
    }

    .transaction-details-page .transaction-btn-green:hover {
        background: #237354;
    }

    .transaction-details-page .transaction-btn-red {
        background: #dc2626;
        color: #ffffff;
    }

    .transaction-details-page .transaction-btn-red:hover {
        background: #b91c1c;
    }

    .transaction-alert-success {
        background: #d1fae5;
        color: #065f46;
        padding: 14px 16px;
        border-radius: 8px;
        margin-bottom: 22px;
    }

    .transaction-alert-error {
        background: #fee2e2;
        color: #991b1b;
        padding: 14px 16px;
        border-radius: 8px;
        margin-bottom: 22px;
    }

    @media (max-width: 768px) {
        .transaction-details-page {
            padding: 20px 14px 35px;
        }

        .transaction-details-card {
            padding: 20px;
        }

        .transaction-details-grid {
            grid-template-columns: 1fr;
        }

        .transaction-financial-summary {
            grid-template-columns: 1fr;
        }

        .transaction-details-heading h1 {
            font-size: 22px;
        }
    }
</style>

<div class="transaction-details-page">

    <div class="transaction-details-heading">
        <h1>Transaction Details</h1>
        <p>
            View membership transaction information,
            payment records, and approval status.
        </p>
    </div>

    <div class="transaction-details-card">

        @if(session('success'))
            <div class="transaction-alert-success" role="status">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="transaction-alert-error" role="alert">
                {{ session('error') }}
            </div>
        @endif

        <div class="transaction-details-section">

            <h2>Transaction Information</h2>

            @php
                $statusClass = match($transaction->status) {
                    'Pending' => 'transaction-status-pending',
                    'Paid' => 'transaction-status-paid',
                    'Approved' => 'transaction-status-approved',
                    'Cancelled' => 'transaction-status-cancelled',
                    default => 'transaction-status-pending',
                };
            @endphp

            <div class="transaction-details-grid">

                <div class="transaction-detail-item">
                    <strong>Transaction Reference</strong>
                    <span>{{ $transaction->reference_number }}</span>
                </div>

                <div class="transaction-detail-item">
                    <strong>Member Name</strong>
                    <span>{{ $transaction->member?->name ?? 'Unknown Member' }}</span>
                </div>

                <div class="transaction-detail-item">
                    <strong>Membership Number</strong>
                    <span>
                        {{ $transaction->member?->membership_number ?? 'Unavailable' }}
                    </span>
                </div>

                <div class="transaction-detail-item">
                    <strong>Membership Level</strong>
                    <span>
                        {{ $transaction->member?->level ?? 'Unavailable' }}
                    </span>
                </div>

                <div class="transaction-detail-item">
                    <strong>Transaction Type</strong>
                    <span>{{ $transaction->transaction_type }}</span>
                </div>

                <div class="transaction-detail-item">
                    <strong>Transaction Status</strong>

                    <span class="transaction-status-badge {{ $statusClass }}">
                        {{ $transaction->status }}
                    </span>
                </div>

                <div class="transaction-detail-item">
                    <strong>Membership Start Date</strong>
                    <span>
                        {{ $transaction->start_date?->format('F d, Y') ?? 'Not yet approved' }}
                    </span>
                </div>

                <div class="transaction-detail-item">
                    <strong>Membership Expiration Date</strong>
                    <span>
                        {{ $transaction->end_date?->format('F d, Y') ?? 'Not yet approved' }}
                    </span>
                </div>

            </div>

            <div class="transaction-financial-summary">

                <div class="transaction-financial-item">
                    <strong>Membership Fee</strong>
                    <span>
                        ₱{{ number_format($transaction->amount, 2) }}
                    </span>
                </div>

                <div class="transaction-financial-item">
                    <strong>Total Paid</strong>
                    <span>
                        ₱{{ number_format($totalPaid, 2) }}
                    </span>
                </div>

                <div class="transaction-financial-item balance">
                    <strong>Remaining Balance</strong>
                    <span>
                        ₱{{ number_format($balance, 2) }}
                    </span>
                </div>

            </div>

        </div>

        <hr class="transaction-section-divider">

        <div class="transaction-details-section">

            <h2>Payment History</h2>

            <div class="transaction-payment-table-wrapper">

                <table class="transaction-payment-table">

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
                                <td>
                                    {{ $payment->payment_reference }}
                                </td>

                                <td>
                                    ₱{{ number_format($payment->amount, 2) }}
                                </td>

                                <td>
                                    {{ $payment->payment_method }}
                                </td>

                                <td>
                                    {{ $payment->payment_date?->format('F d, Y') ?? 'N/A' }}
                                </td>
                            </tr>

                        @empty

                            <tr>
                                <td colspan="4" class="transaction-payment-empty">
                                    No payments recorded for this transaction.
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

        <div class="transaction-details-actions">

            @if($transaction->status === 'Pending' && $balance > 0)

                <a href="{{ route('payments.create', $transaction) }}"
                   class="transaction-action-btn transaction-btn-green">
                    + Record Payment
                </a>

            @endif

            @if($transaction->status === 'Paid')

                <form action="{{ route('transactions.approve', $transaction) }}"
                      method="POST"
                      onsubmit="return confirm('Approve this transaction?');">

                    @csrf
                    @method('PATCH')

                    <button type="submit"
                            class="transaction-action-btn transaction-btn-green">
                        Approve Transaction
                    </button>

                </form>

            @endif

            @if($transaction->status === 'Pending')

                <form action="{{ route('transactions.cancel', $transaction) }}"
                      method="POST"
                      onsubmit="return confirm('Cancel this transaction?');">

                    @csrf
                    @method('PATCH')

                    <button type="submit"
                            class="transaction-action-btn transaction-btn-red">
                        Cancel Transaction
                    </button>

                </form>

            @endif

            <a href="{{ route('transactions.receipt', $transaction) }}"
               class="transaction-action-btn transaction-btn-primary"
               target="_blank"
               rel="noopener">
                Print Receipt
            </a>

            <a href="{{ route('transactions.index') }}"
               class="transaction-action-btn transaction-btn-secondary">
                Back to Transactions
            </a>

        </div>

    </div>

</div>

@endsection
