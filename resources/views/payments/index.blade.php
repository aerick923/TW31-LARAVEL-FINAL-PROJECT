
@extends('layouts.admin')

@section('title', 'Payment Management')
@section('page-title', 'Payment Management')

@push('styles')
<style>
    .payments-page {
        width: 100%;
    }

    .payments-page .payments-card {
        background: white;
        border: 1px solid #ded6ff;
        border-radius: 15px;
        padding: 28px;
        box-shadow: 0 8px 25px rgba(48, 35, 107, 0.05);
    }

    .payments-page h1 {
        color: #30236b;
        font-size: 26px;
        margin: 0 0 8px;
    }

    .payments-page .subtitle {
        color: #74649d;
        font-size: 13px;
        margin: 0 0 25px;
    }

    .payments-page .filter-panel {
        background: #faf9ff;
        border: 1px solid #ded6ff;
        border-radius: 12px;
        padding: 22px;
        margin-bottom: 25px;
    }

    .payments-page .filter-panel label {
        display: block;
        color: #30236b;
        font-size: 13px;
        font-weight: 600;
        margin-bottom: 8px;
    }

    .payments-page .filter-panel input,
    .payments-page .filter-panel select {
        width: 100%;
        min-width: 0;
        padding: 12px;
        border: 1px solid #dcd3ff;
        border-radius: 8px;
        background: white;
        color: #30236b;
        font-size: 13px;
    }

    .payments-page .filter-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 15px;
        margin-top: 18px;
    }

    .payments-page .filter-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-top: 20px;
    }

    .payments-page .payment-btn {
        display: inline-block;
        border: 0;
        border-radius: 8px;
        padding: 12px 16px;
        background: #8064ed;
        color: white;
        text-decoration: none;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
    }

    .payments-page .payment-btn:hover {
        background: #6d52d6;
    }

    .payments-page .btn-gray {
        background: #4d5b80;
    }

    .payments-page .btn-gray:hover {
        background: #3c4969;
    }

    .payments-page .table-wrapper {
        width: 100%;
        overflow-x: auto;
        border: 1px solid #ded6ff;
        border-radius: 10px;
    }

    .payments-page table {
        width: 100%;
        border-collapse: collapse;
        font-size: 13px;
    }

    .payments-page th {
        background: #eee9ff;
        color: #30236b;
        text-align: left;
        padding: 15px;
        font-weight: 700;
        white-space: nowrap;
    }

    .payments-page td {
        padding: 15px;
        border-top: 1px solid #e8e1ff;
        color: #30236b;
        vertical-align: middle;
    }

    .payments-page .amount {
        font-weight: 700;
        white-space: nowrap;
    }

    .payments-page .empty-message {
        text-align: center;
        padding: 30px;
        color: #74649d;
    }

    .payments-page .payment-summary {
        display: flex;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
        margin-bottom: 18px;
        color: #74649d;
        font-size: 13px;
    }

    .payments-page .pagination {
        margin-top: 22px;
    }

    @media (max-width: 768px) {
        .payments-page .payments-card {
            padding: 18px;
        }

        .payments-page .filter-grid {
            grid-template-columns: 1fr;
        }

        .payments-page .filter-panel {
            padding: 16px;
        }
    }
</style>
@endpush

@section('content')

<div class="payments-page">

    <div class="payments-card">

        <h1>Recorded Payments</h1>

        <p class="subtitle">
            Search, filter, and review membership payment records.
        </p>

        @if($errors->any())
            <div class="error-box" role="alert">
                @foreach($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <!-- PAYMENT SEARCH AND FILTERS -->

        <form method="GET"
              action="{{ route('payments.index') }}"
              class="filter-panel">

            <div>
                <label for="search">
                    Search Payments
                </label>

                <input
                    type="text"
                    id="search"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Payment reference, transaction, member name, or membership number"
                    maxlength="255"
                >
            </div>

            <div class="filter-grid">

                <div>
                    <label for="payment_method">
                        Payment Method
                    </label>

                    <select name="payment_method"
                            id="payment_method">

                        <option value="">All Methods</option>

                        @foreach(['Cash', 'GCash', 'Bank Transfer'] as $method)
                            <option value="{{ $method }}"
                                @selected(request('payment_method') === $method)>
                                {{ $method }}
                            </option>
                        @endforeach

                    </select>
                </div>

                <div>
                    <label for="date_from">
                        Date From
                    </label>

                    <input
                        type="date"
                        id="date_from"
                        name="date_from"
                        value="{{ request('date_from') }}"
                    >
                </div>

                <div>
                    <label for="date_to">
                        Date To
                    </label>

                    <input
                        type="date"
                        id="date_to"
                        name="date_to"
                        value="{{ request('date_to') }}"
                    >
                </div>

            </div>

            <div class="filter-actions">

                <button type="submit"
                        class="payment-btn">
                    Search Payments
                </button>

                <a href="{{ route('payments.index') }}"
                   class="payment-btn btn-gray">
                    Clear Filters
                </a>

            </div>

        </form>

        <!-- PAYMENT RESULTS -->

        <div class="payment-summary">

            <span>
                Showing {{ $payments->firstItem() ?? 0 }}
                to {{ $payments->lastItem() ?? 0 }}
                of {{ $payments->total() }} payments
            </span>

        </div>

        <div class="table-wrapper">

            <table>

                <thead>
                    <tr>
                        <th>Payment Reference</th>
                        <th>Member</th>
                        <th>Transaction Reference</th>
                        <th>Amount</th>
                        <th>Payment Method</th>
                        <th>Payment Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($payments as $payment)

                        @php
                            $transaction = $payment->membershipTransaction;
                            $member = $transaction?->member;
                        @endphp

                        <tr>

                            <td>
                                {{ $payment->payment_reference }}
                            </td>

                            <td>
                                {{ $member?->name ?? 'Unavailable' }}
                            </td>

                            <td>
                                {{ $transaction?->reference_number ?? 'Unavailable' }}
                            </td>

                            <td class="amount">
                                ₱{{ number_format((float) $payment->amount, 2) }}
                            </td>

                            <td>
                                {{ $payment->payment_method }}
                            </td>

                            <td>
                                {{ $payment->payment_date?->format('M d, Y') ?? 'Unavailable' }}
                            </td>

                            <td>
                                @if($transaction)
                                    <a href="{{ route('transactions.show', $transaction) }}"
                                       class="payment-btn">
                                        View Transaction
                                    </a>
                                @else
                                    <span>Unavailable</span>
                                @endif
                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="7"
                                class="empty-message">
                                No payment records found.
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        <div class="pagination">
            {{ $payments->links() }}
        </div>

    </div>

</div>

@endsection
