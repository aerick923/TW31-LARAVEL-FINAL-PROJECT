
@extends('layouts.admin')

@section('title', 'Record Payment')

@section('page-title', 'Record Payment')

@section('content')

<style>
    .payment-create-page {
        width: 100%;
        max-width: 1100px;
        margin: 0 auto;
        padding: 32px 24px 50px;
    }

    .payment-create-heading {
        margin-bottom: 24px;
    }

    .payment-create-heading h1 {
        font-size: 26px;
        font-weight: 700;
        color: #28245e;
        margin: 0 0 8px;
    }

    .payment-create-heading p {
        font-size: 14px;
        color: #756b9e;
        margin: 0;
    }

    .payment-create-card {
        max-width: 720px;
        margin: 0 auto;
        padding: 30px;
        background: #ffffff;
        border: 1px solid #ddd6fe;
        border-radius: 14px;
        box-shadow: 0 8px 24px rgba(48, 35, 107, 0.06);
    }

    .payment-create-card h2 {
        font-size: 21px;
        font-weight: 700;
        color: #30236b;
        margin: 0 0 10px;
    }

    .payment-create-description {
        font-size: 14px;
        color: #756b9e;
        margin: 0 0 24px;
    }

    .payment-summary {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 14px;
        margin-bottom: 24px;
    }

    .payment-summary-item {
        padding: 16px;
        background: #f8f7ff;
        border: 1px solid #e8e2ff;
        border-radius: 10px;
        min-width: 0;
    }

    .payment-summary-item strong {
        display: block;
        color: #6253a5;
        font-size: 12px;
        margin-bottom: 9px;
    }

    .payment-summary-item span {
        display: block;
        color: #28245e;
        font-size: 14px;
        font-weight: 600;
        overflow-wrap: anywhere;
    }

    .payment-summary-item.balance {
        background: #fffbeb;
        border-color: #fde68a;
        grid-column: 1 / -1;
    }

    .payment-summary-item.balance span {
        color: #92400e;
        font-size: 23px;
        font-weight: 700;
    }

    .payment-form-divider {
        border: none;
        border-top: 1px solid #e8e2ff;
        margin: 26px 0;
    }

    .payment-form-heading {
        font-size: 17px;
        font-weight: 700;
        color: #30236b;
        margin: 0 0 22px;
    }

    .payment-form-group {
        margin-bottom: 22px;
    }

    .payment-form-group label {
        display: block;
        font-size: 13px;
        font-weight: 600;
        color: #30236b;
        margin-bottom: 9px;
    }

    .payment-form-group input,
    .payment-form-group select {
        display: block;
        width: 100%;
        padding: 12px 14px;
        border: 1px solid #dcd3ff;
        border-radius: 8px;
        background: #ffffff;
        color: #28245e;
        font-size: 14px;
        box-sizing: border-box;
    }

    .payment-form-group input:focus,
    .payment-form-group select:focus {
        outline: none;
        border-color: #8768ef;
        box-shadow: 0 0 0 3px rgba(135, 104, 239, 0.12);
    }

    .payment-form-hint {
        display: block;
        margin-top: 7px;
        font-size: 12px;
        color: #756b9e;
    }

    .payment-form-error {
        margin-top: 7px;
        font-size: 12px;
        color: #b91c1c;
    }

    .payment-alert-error {
        padding: 14px 16px;
        margin-bottom: 22px;
        background: #fee2e2;
        color: #991b1b;
        border-radius: 8px;
        font-size: 13px;
    }

    .payment-alert-error ul {
        margin: 10px 0 0;
        padding-left: 20px;
    }

    .payment-form-actions {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 10px;
        margin-top: 26px;
    }

    .payment-create-page .payment-action-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 12px 18px;
        border: none;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
        cursor: pointer;
        transition: background 0.2s ease;
    }

    .payment-create-page .payment-btn-green {
        background: #2e8b68;
        color: #ffffff;
    }

    .payment-create-page .payment-btn-green:hover {
        background: #237354;
    }

    .payment-create-page .payment-btn-secondary {
        background: #495879;
        color: #ffffff;
    }

    .payment-create-page .payment-btn-secondary:hover {
        background: #354361;
    }

    @media (max-width: 768px) {
        .payment-create-page {
            padding: 20px 14px 35px;
        }

        .payment-create-card {
            padding: 20px;
        }

        .payment-create-heading h1 {
            font-size: 22px;
        }

        .payment-summary {
            grid-template-columns: 1fr;
        }

        .payment-summary-item.balance {
            grid-column: auto;
        }
    }
</style>

<div class="payment-create-page">

    <div class="payment-create-heading">
        <h1>Record Payment</h1>
        <p>
            Record a payment for an existing
            membership transaction.
        </p>
    </div>

    <div class="payment-create-card">

        <h2>Payment Information</h2>

        <p class="payment-create-description">
            Review the transaction details and enter
            the payment information below.
        </p>

        @if(session('error'))
            <div class="payment-alert-error" role="alert">
                {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div class="payment-alert-error" role="alert">
                <strong>Please correct the following errors:</strong>

                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="payment-summary">

            <div class="payment-summary-item">
                <strong>Transaction Reference</strong>
                <span>{{ $transaction->reference_number }}</span>
            </div>

            <div class="payment-summary-item">
                <strong>Member</strong>
                <span>{{ $transaction->member?->name ?? 'Unknown Member' }}</span>
            </div>

            <div class="payment-summary-item">
                <strong>Membership Fee</strong>
                <span>
                    ₱{{ number_format($transaction->amount, 2) }}
                </span>
            </div>

            <div class="payment-summary-item">
                <strong>Total Paid</strong>
                <span>
                    ₱{{ number_format($totalPaid, 2) }}
                </span>
            </div>

            <div class="payment-summary-item balance">
                <strong>Remaining Balance</strong>
                <span>
                    ₱{{ number_format($balance, 2) }}
                </span>
            </div>

        </div>

        @if($transaction->status === 'Pending' && $balance > 0)

            <hr class="payment-form-divider">

            <h3 class="payment-form-heading">
                Enter Payment Details
            </h3>

            <form action="{{ route('payments.store', $transaction) }}"
                  method="POST">

                @csrf

                <div class="payment-form-group">

                    <label for="amount">
                        Payment Amount (₱)
                    </label>

                    <input
                        type="number"
                        id="amount"
                        name="amount"
                        min="0.01"
                        max="{{ number_format($balance, 2, '.', '') }}"
                        step="0.01"
                        value="{{ old('amount') }}"
                        placeholder="Enter payment amount"
                        required
                    >

                    <small class="payment-form-hint">
                        Maximum payment:
                        ₱{{ number_format($balance, 2) }}
                    </small>

                    @error('amount')
                        <div class="payment-form-error">
                            {{ $message }}
                        </div>
                    @enderror

                </div>

                <div class="payment-form-group">

                    <label for="payment_method">
                        Payment Method
                    </label>

                    <select
                        id="payment_method"
                        name="payment_method"
                        required
                    >

                        <option value="">
                            Select payment method
                        </option>

                        @foreach(['Cash', 'GCash', 'Bank Transfer'] as $method)

                            <option
                                value="{{ $method }}"
                                @selected(old('payment_method') === $method)
                            >
                                {{ $method }}
                            </option>

                        @endforeach

                    </select>

                    @error('payment_method')
                        <div class="payment-form-error">
                            {{ $message }}
                        </div>
                    @enderror

                </div>

                <div class="payment-form-group">

                    <label for="payment_date">
                        Payment Date
                    </label>

                    <input
                        type="date"
                        id="payment_date"
                        name="payment_date"
                        max="{{ now()->toDateString() }}"
                        value="{{ old('payment_date', now()->toDateString()) }}"
                        required
                    >

                    @error('payment_date')
                        <div class="payment-form-error">
                            {{ $message }}
                        </div>
                    @enderror

                </div>

                <div class="payment-form-actions">

                    <button
                        type="submit"
                        class="payment-action-btn payment-btn-green"
                    >
                        Record Payment
                    </button>

                    <a href="{{ route('transactions.show', $transaction) }}"
                       class="payment-action-btn payment-btn-secondary">
                        Cancel
                    </a>

                </div>

            </form>

        @else

            <div class="payment-alert-error" role="alert">
                This transaction cannot accept additional payments.
            </div>

            <div class="payment-form-actions">

                <a href="{{ route('transactions.show', $transaction) }}"
                   class="payment-action-btn payment-btn-secondary">
                    Back to Transaction
                </a>

            </div>

        @endif

    </div>

</div>

@endsection
