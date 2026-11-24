
@extends('layouts.admin')

@section('title', 'Transaction History')

@section('page-title', 'Transaction History')

@section('content')

<style>
    .transaction-history-page {
        width: 100%;
        max-width: 1200px;
        margin: 0 auto;
        padding: 32px 24px 50px;
        box-sizing: border-box;
    }

    .transaction-history-heading {
        margin-bottom: 24px;
    }

    .transaction-history-heading h1 {
        margin: 0 0 8px;
        color: #28245e;
        font-size: 26px;
        font-weight: 700;
    }

    .transaction-history-heading p {
        margin: 0;
        color: #756b9e;
        font-size: 14px;
    }

    .transaction-history-card {
        background: #ffffff;
        border: 1px solid #ddd6fe;
        border-radius: 14px;
        padding: 28px;
        box-shadow: 0 8px 24px rgba(48, 35, 107, 0.06);
    }

    .transaction-history-card h2 {
        margin: 0 0 20px;
        color: #30236b;
        font-size: 21px;
        font-weight: 700;
    }

    .transaction-member-summary {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 14px;
        margin-bottom: 26px;
    }

    .transaction-member-item {
        padding: 16px;
        background: #f8f7ff;
        border: 1px solid #e8e2ff;
        border-radius: 10px;
        min-width: 0;
    }

    .transaction-member-item strong {
        display: block;
        margin-bottom: 9px;
        color: #6253a5;
        font-size: 12px;
        font-weight: 600;
    }

    .transaction-member-item span {
        display: block;
        color: #28245e;
        font-size: 14px;
        font-weight: 600;
        overflow-wrap: anywhere;
    }

    .transaction-history-toolbar {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 10px;
        margin-bottom: 28px;
    }

    .transaction-history-page .history-btn {
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

    .transaction-history-page .history-btn-primary {
        background: #8768ef;
        color: #ffffff;
    }

    .transaction-history-page .history-btn-primary:hover {
        background: #7154d6;
    }

    .transaction-history-page .history-btn-secondary {
        background: #495879;
        color: #ffffff;
    }

    .transaction-history-page .history-btn-secondary:hover {
        background: #354361;
    }

    .transaction-history-section {
        margin-top: 8px;
    }

    .transaction-history-section h3 {
        margin: 0 0 18px;
        color: #30236b;
        font-size: 18px;
        font-weight: 700;
    }

    .transaction-history-table-wrapper {
        width: 100%;
        overflow-x: auto;
        border: 1px solid #8b79dd;
        border-radius: 10px;
    }

    .transaction-history-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 900px;
        background: #ffffff;
    }

    .transaction-history-table th {
        padding: 15px 13px;
        background: #ede9fe;
        color: #30236b;
        font-size: 12px;
        font-weight: 700;
        text-align: left;
        white-space: nowrap;
    }

    .transaction-history-table td {
        padding: 15px 13px;
        color: #28245e;
        font-size: 13px;
        border-top: 1px solid #e8e2ff;
        vertical-align: middle;
    }

    .transaction-history-table tbody tr:hover {
        background: #faf8ff;
    }

    .transaction-history-table .history-reference {
        font-weight: 600;
        overflow-wrap: anywhere;
    }

    .transaction-history-table .history-amount {
        white-space: nowrap;
    }

    .history-status {
        display: inline-block;
        padding: 7px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        white-space: nowrap;
    }

    .history-status-approved {
        background: #d1fae5;
        color: #065f46;
    }

    .history-status-pending {
        background: #fef3c7;
        color: #92400e;
    }

    .history-status-paid {
        background: #dbeafe;
        color: #1d4ed8;
    }

    .history-status-cancelled {
        background: #fee2e2;
        color: #991b1b;
    }

    .history-status-default {
        background: #f3f4f6;
        color: #374151;
    }

    .history-empty-message {
        padding: 28px !important;
        text-align: center !important;
        color: #756b9e !important;
    }

    .transaction-history-pagination {
        margin-top: 24px;
        overflow-x: auto;
    }

    @media (max-width: 768px) {
        .transaction-history-page {
            padding: 20px 14px 35px;
        }

        .transaction-history-heading h1 {
            font-size: 22px;
        }

        .transaction-history-card {
            padding: 20px;
        }

        .transaction-member-summary {
            grid-template-columns: 1fr;
        }

        .transaction-history-toolbar {
            align-items: stretch;
        }
    }
</style>

<div class="transaction-history-page">

    <div class="transaction-history-heading">
        <h1>Transaction History</h1>
        <p>
            View membership transactions,
            payment records, and validity periods.
        </p>
    </div>

    <div class="transaction-history-card">

        <h2>Member Information</h2>

        <div class="transaction-member-summary">

            <div class="transaction-member-item">
                <strong>Member Name</strong>
                <span>{{ $member->name }}</span>
            </div>

            <div class="transaction-member-item">
                <strong>Membership Number</strong>
                <span>{{ $member->membership_number }}</span>
            </div>

            <div class="transaction-member-item">
                <strong>Membership Level</strong>
                <span>{{ $member->level }}</span>
            </div>

        </div>

        <div class="transaction-history-toolbar">

            <a href="{{ route('members.show', $member) }}"
               class="history-btn history-btn-secondary">
                Back to Member
            </a>

            <a href="{{ route('transactions.index') }}"
               class="history-btn history-btn-primary">
                All Transactions
            </a>

        </div>

        <div class="transaction-history-section">

            <h3>Membership Transactions</h3>

            <div class="transaction-history-table-wrapper">

                <table class="transaction-history-table">

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

                                <td class="history-reference">
                                    {{ $transaction->reference_number }}
                                </td>

                                <td>
                                    {{ $transaction->transaction_type }}
                                </td>

                                <td class="history-amount">
                                    ₱{{ number_format($transaction->amount, 2) }}
                                </td>

                                <td class="history-amount">
                                    ₱{{ number_format(
                                        $transaction->payments->sum('amount'),
                                        2
                                    ) }}
                                </td>

                                <td>
                                    @php
                                        $status = strtolower($transaction->status);

                                        $statusClass = match ($status) {
                                            'approved' => 'history-status-approved',
                                            'pending' => 'history-status-pending',
                                            'paid' => 'history-status-paid',
                                            'cancelled', 'canceled' => 'history-status-cancelled',
                                            default => 'history-status-default',
                                        };
                                    @endphp

                                    <span class="history-status {{ $statusClass }}">
                                        {{ $transaction->status }}
                                    </span>
                                </td>

                                <td>
                                    {{ $transaction->start_date?->format('M d, Y') ?? '—' }}
                                </td>

                                <td>
                                    {{ $transaction->end_date?->format('M d, Y') ?? '—' }}
                                </td>

                                <td>
                                    <a
                                        href="{{ route('transactions.show', $transaction) }}"
                                        class="history-btn history-btn-primary"
                                    >
                                        View Payments
                                    </a>
                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="8" class="history-empty-message">
                                    No transaction history available.
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

            <div class="transaction-history-pagination">
                {{ $transactions->links() }}
            </div>

        </div>

    </div>

</div>

@endsection
