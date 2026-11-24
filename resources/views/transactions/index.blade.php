
@extends('layouts.admin')

@section('title', 'Transaction Management')

@section('page-title', 'Transaction Management')

@section('content')


<style>
    .transactions-page {
        width: 100%;
        max-width: 1150px;
        margin: 0 auto;
        padding: 32px 24px 50px;
    }

    .transactions-heading {
        margin-bottom: 24px;
    }

    .transactions-heading h1 {
        font-size: 26px;
        font-weight: 700;
        color: #28245e;
        margin: 0 0 8px;
    }

    .transactions-heading p {
        color: #756b9e;
        font-size: 14px;
        margin: 0;
    }

    .transactions-card {
        background: #ffffff;
        border: 1px solid #ddd6fe;
        border-radius: 14px;
        padding: 26px;
        box-shadow: 0 8px 24px rgba(48, 35, 107, 0.06);
    }

    .transactions-card h2 {
        font-size: 21px;
        color: #30236b;
        margin: 0 0 22px;
    }

    .transactions-toolbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
        margin-bottom: 22px;
    }

    .transactions-toolbar-actions {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }

    .transactions-page .transaction-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 11px 16px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
        white-space: nowrap;
        transition: background 0.2s ease;
    }

    .transactions-page .transaction-btn-primary {
        background: #8768ef;
        color: #ffffff;
    }

    .transactions-page .transaction-btn-primary:hover {
        background: #7053d6;
    }

    .transactions-page .transaction-btn-secondary {
        background: #495879;
        color: #ffffff;
    }

    .transactions-page .transaction-btn-secondary:hover {
        background: #354361;
    }

    .transactions-page .transaction-btn-green {
        background: #2e8b68;
        color: #ffffff;
    }

    .transactions-page .transaction-btn-green:hover {
        background: #237354;
    }

    .transactions-table-wrapper {
        width: 100%;
        overflow-x: auto;
        border: 1px solid #7770b8;
        border-radius: 10px;
    }

    .transactions-table {
        width: 100%;
        min-width: 850px;
        border-collapse: collapse;
        font-size: 13px;
    }

    .transactions-table thead {
        background: #eee8ff;
    }

    .transactions-table th {
        color: #30236b;
        font-weight: 700;
        padding: 15px 13px;
        text-align: left;
        white-space: nowrap;
    }

    .transactions-table td {
        color: #28245e;
        padding: 14px 13px;
        border-top: 1px solid #e8e2f8;
        vertical-align: middle;
    }

    .transactions-table tbody tr:hover {
        background: #faf8ff;
    }

    .transaction-status {
        display: inline-block;
        padding: 6px 11px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }

    .transaction-status-pending {
        background: #fef3c7;
        color: #92400e;
    }

    .transaction-status-paid {
        background: #dbeafe;
        color: #1d4ed8;
    }

    .transaction-status-approved {
        background: #d1fae5;
        color: #065f46;
    }

    .transaction-status-cancelled {
        background: #fee2e2;
        color: #991b1b;
    }

    .transaction-actions {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 8px;
    }

    .transaction-actions .transaction-btn {
        padding: 9px 13px;
        font-size: 12px;
    }

    .transactions-empty {
        padding: 25px !important;
        text-align: center;
        color: #756b9e !important;
    }

    .transactions-alert-success {
        background: #d1fae5;
        color: #065f46;
        padding: 14px 16px;
        border-radius: 8px;
        margin-bottom: 20px;
    }

    .transactions-alert-error {
        background: #fee2e2;
        color: #991b1b;
        padding: 14px 16px;
        border-radius: 8px;
        margin-bottom: 20px;
    }

    .transactions-pagination {
        margin-top: 24px;
    }

    @media (max-width: 768px) {
        .transactions-page {
            padding: 20px 14px 35px;
        }

        .transactions-card {
            padding: 18px;
        }

        .transactions-heading h1 {
            font-size: 22px;
        }

        .transactions-toolbar {
            align-items: stretch;
        }
    }
</style>

<div class="transactions-page">

    <div class="transactions-heading">
        <h1>Transaction Management</h1>

        <p>
            Manage membership transactions,
            payments, and renewals.
        </p>
    </div>

    <div class="transactions-card">

        <h2>Membership Transactions</h2>

        @if(session('success'))
            <div class="transactions-alert-success" role="status">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="transactions-alert-error" role="alert">
                {{ session('error') }}
            </div>
        @endif

        <div class="transactions-toolbar">



            <a href="{{ route('transactions.create') }}"
               class="transaction-btn transaction-btn-green">
                + New Transaction
            </a>

        </div>

        <div class="transactions-table-wrapper">

            <table class="transactions-table">

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
                                {{ $transaction->member?->name ?? 'Unknown Member' }}
                            </td>

                            <td>
                                {{ $transaction->transaction_type }}
                            </td>

                            <td>
                                ₱{{ number_format($transaction->amount, 2) }}
                            </td>

                            <td>
                                @php
                                    $statusClass = match($transaction->status) {
                                        'Pending' => 'transaction-status-pending',
                                        'Paid' => 'transaction-status-paid',
                                        'Approved' => 'transaction-status-approved',
                                        'Cancelled' => 'transaction-status-cancelled',
                                        default => 'transaction-status-pending',
                                    };
                                @endphp

                                <span class="transaction-status {{ $statusClass }}">
                                    {{ $transaction->status }}
                                </span>
                            </td>

                            <td>
                                {{ $transaction->created_at?->format('M d, Y') ?? 'N/A' }}
                            </td>

                            <td>
                                <div class="transaction-actions">

                                    <a href="{{ route('transactions.show', $transaction) }}"
                                       class="transaction-btn transaction-btn-primary">
                                        View
                                    </a>

                                    @if($transaction->member)
                                        <a href="{{ route('transactions.history', $transaction->member) }}"
                                           class="transaction-btn transaction-btn-secondary">
                                            History
                                        </a>
                                    @endif

                                </div>
                            </td>
                        </tr>

                    @empty

                        <tr>
                            <td colspan="7" class="transactions-empty">
                                No membership transactions found.
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        <div class="transactions-pagination">
            {{ $transactions->links() }}
        </div>

    </div>

</div>

@endsection
