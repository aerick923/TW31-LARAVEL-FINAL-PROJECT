
@php
    $isAdmin = auth()->user()?->role === 'admin';

    $receiptStatus = strtolower($transaction->status);

    $receiptStatusClass = match ($receiptStatus) {
        'approved' => 'receipt-status-approved',
        'pending' => 'receipt-status-pending',
        'paid' => 'receipt-status-paid',
        'cancelled', 'canceled' => 'receipt-status-cancelled',
        default => 'receipt-status-default',
    };
@endphp

@if($isAdmin)
    @extends('layouts.admin')

    @section('title', 'Membership Payment Receipt')
    @section('page-title', 'Payment Receipt')
@endif

@push('styles')
<style>
    .receipt-page {
        width: 100%;
        max-width: 900px;
        margin: 0 auto;
        padding: 25px 0 45px;
        box-sizing: border-box;
        font-family: Arial, Helvetica, sans-serif;
        color: #28245e;
    }

    .receipt-page * {
        box-sizing: border-box;
    }

    .receipt-page-heading {
        margin-bottom: 24px;
    }

    .receipt-page-heading h1 {
        margin: 0 0 8px;
        color: #28245e;
        font-size: 27px;
    }

    .receipt-page-heading p {
        margin: 0;
        color: #756b9e;
        font-size: 14px;
    }

    .receipt-card {
        background: #ffffff;
        border: 1px solid #ddd6fe;
        border-radius: 14px;
        padding: 32px;
        box-shadow: 0 10px 30px rgba(48, 35, 107, 0.06);
    }

    .receipt-heading {
        text-align: center;
        padding-bottom: 24px;
        border-bottom: 1px solid #e8e2ff;
    }

    .receipt-heading h2 {
        margin: 0 0 8px;
        color: #30236b;
        font-size: 24px;
    }

    .receipt-heading p {
        margin: 5px 0;
        color: #756b9e;
        font-size: 13px;
    }

    .receipt-reference {
        display: inline-block;
        margin-top: 14px;
        padding: 8px 14px;
        background: #f3efff;
        border: 1px solid #e8e2ff;
        border-radius: 8px;
        color: #50429a;
        font-size: 13px;
        font-weight: 700;
    }

    .receipt-section {
        margin-top: 27px;
    }

    .receipt-section h3 {
        margin: 0 0 16px;
        color: #30236b;
        font-size: 18px;
    }

    .receipt-information-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 12px;
    }

    .receipt-information-item {
        padding: 15px;
        background: #f8f7ff;
        border: 1px solid #e8e2ff;
        border-radius: 9px;
        min-width: 0;
    }

    .receipt-information-item strong {
        display: block;
        margin-bottom: 7px;
        color: #6253a5;
        font-size: 12px;
    }

    .receipt-information-item span {
        display: block;
        color: #28245e;
        font-size: 14px;
        font-weight: 600;
        overflow-wrap: anywhere;
    }

    .receipt-status {
        display: inline-block !important;
        width: fit-content;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 12px !important;
        font-weight: 700;
    }

    .receipt-status-approved {
        background: #d1fae5;
        color: #065f46 !important;
    }

    .receipt-status-pending {
        background: #fef3c7;
        color: #92400e !important;
    }

    .receipt-status-paid {
        background: #dbeafe;
        color: #1d4ed8 !important;
    }

    .receipt-status-cancelled {
        background: #fee2e2;
        color: #991b1b !important;
    }

    .receipt-status-default {
        background: #f3f4f6;
        color: #374151 !important;
    }

    .receipt-payment-summary {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 12px;
    }

    .receipt-payment-item {
        padding: 18px 15px;
        background: #f8f7ff;
        border: 1px solid #e8e2ff;
        border-radius: 10px;
        min-width: 0;
    }

    .receipt-payment-item strong {
        display: block;
        margin-bottom: 9px;
        color: #6253a5;
        font-size: 12px;
    }

    .receipt-payment-item span {
        display: block;
        color: #30236b;
        font-size: 20px;
        font-weight: 700;
        overflow-wrap: anywhere;
    }

    .receipt-payment-item.balance {
        background: #fffbeb;
        border-color: #fcd34d;
    }

    .receipt-payment-item.balance span {
        color: #92400e;
    }

    .receipt-table-wrapper {
        width: 100%;
        overflow-x: auto;
        border: 1px solid #8b79dd;
        border-radius: 9px;
    }

    .receipt-table {
        width: 100%;
        min-width: 570px;
        border-collapse: collapse;
    }

    .receipt-table th {
        background: #ede9fe;
        color: #30236b;
        padding: 13px;
        text-align: left;
        font-size: 12px;
    }

    .receipt-table td {
        padding: 13px;
        border-top: 1px solid #e8e2ff;
        color: #28245e;
        font-size: 13px;
    }

    .receipt-empty {
        text-align: center;
        padding: 24px !important;
        color: #756b9e !important;
    }

    .receipt-note {
        margin-top: 27px;
        padding: 15px 17px;
        background: #f8f7ff;
        border: 1px solid #e8e2ff;
        border-radius: 9px;
        color: #625b83;
        font-size: 13px;
    }

    .receipt-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-top: 27px;
    }

    .receipt-page .receipt-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 11px 18px;
        border: none;
        border-radius: 8px;
        font-family: inherit;
        font-size: 13px;
        font-weight: 700;
        text-decoration: none;
        cursor: pointer;
    }

    .receipt-page .receipt-btn-primary {
        background: #8768ef;
        color: white;
    }

    .receipt-page .receipt-btn-secondary {
        background: #495879;
        color: white;
    }

    .receipt-footer {
        margin-top: 24px;
        padding-top: 17px;
        border-top: 1px solid #e8e2ff;
        text-align: center;
        color: #756b9e;
        font-size: 12px;
    }

    .receipt-user-topbar {
        background: white;
        border-bottom: 1px solid #e8e2ff;
        padding: 17px 25px;
    }

    .receipt-user-topbar-inner {
        max-width: 900px;
        margin: auto;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .receipt-user-brand {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .receipt-user-logo {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, #30236b, #8768ef);
        border-radius: 10px;
        color: white;
        font-size: 21px;
        font-weight: 800;
    }

    .receipt-user-brand h2 {
        margin: 0;
        font-size: 18px;
        color: #28245e;
    }

    .receipt-user-brand p {
        margin: 0;
        font-size: 12px;
        color: #756b9e;
    }

    .receipt-user-topbar-label {
        color: #756b9e;
        font-size: 13px;
    }

    @media (max-width: 768px) {
        .receipt-page {
            padding: 20px 0 35px;
        }

        .receipt-card {
            padding: 20px;
        }

        .receipt-information-grid,
        .receipt-payment-summary {
            grid-template-columns: 1fr;
        }

        .receipt-user-topbar-label {
            display: none;
        }
    }

    @media print {
        @page {
            size: A4;
            margin: 12mm;
        }

        body,
        html {
            background: white !important;
        }

        .admin-sidebar,
        .admin-topbar,
        .receipt-user-topbar,
        .receipt-page-heading,
        .receipt-actions {
            display: none !important;
        }

        .admin-layout,
        .admin-main,
        .admin-content {
            display: block !important;
            width: 100% !important;
            max-width: none !important;
            min-height: 0 !important;
            margin: 0 !important;
            padding: 0 !important;
        }

        .receipt-page {
            width: 100%;
            max-width: none;
            margin: 0;
            padding: 0;
        }

        .receipt-card {
            border: none !important;
            box-shadow: none !important;
            padding: 0 !important;
        }

        .receipt-heading {
            padding-bottom: 12px;
        }

        .receipt-heading h2 {
            font-size: 21px;
        }

        .receipt-heading p {
            font-size: 11px;
        }

        .receipt-section {
            margin-top: 17px;
        }

        .receipt-section h3 {
            font-size: 15px;
            margin-bottom: 10px;
        }

        .receipt-information-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 8px;
        }

        .receipt-information-item {
            padding: 10px;
            background: white !important;
            border: 1px solid #dddddd;
        }

        .receipt-information-item strong {
            font-size: 10px;
            margin-bottom: 4px;
        }

        .receipt-information-item span {
            font-size: 11px;
        }

        .receipt-payment-summary {
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 8px;
        }

        .receipt-payment-item {
            padding: 12px;
            background: white !important;
            border: 1px solid #dddddd;
        }

        .receipt-payment-item strong {
            font-size: 10px;
        }

        .receipt-payment-item span {
            font-size: 17px;
        }

        .receipt-payment-item.balance {
            background: white !important;
            border: 1px solid #dddddd;
        }

        .receipt-table-wrapper {
            overflow: visible;
            border: 1px solid #dddddd;
        }

        .receipt-table {
            min-width: 0;
        }

        .receipt-table th,
        .receipt-table td {
            padding: 9px;
            font-size: 10px;
        }

        .receipt-note {
            background: white !important;
            border: 1px solid #dddddd;
            padding: 10px;
            font-size: 11px;
        }

        .receipt-information-item,
        .receipt-payment-item,
        .receipt-table tr,
        .receipt-note {
            break-inside: avoid;
        }

        .receipt-status {
            background: white !important;
            color: black !important;
            border: 1px solid #bbbbbb;
        }
    }
</style>
@endpush

@if($isAdmin)
    @section('content')
@endif

@if(!$isAdmin)
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Membership Payment Receipt</title>
    <link rel="stylesheet" href="{{ asset('css/design.css') }}">
    @stack('styles')
</head>
<body>

<div class="receipt-user-topbar">
    <div class="receipt-user-topbar-inner">

        <div class="receipt-user-brand">
            <div class="receipt-user-logo">M</div>

            <div>
                <h2>MemberHub</h2>
                <p>Membership Portal</p>
            </div>
        </div>

        <span class="receipt-user-topbar-label">
            Membership Payment Receipt
        </span>

    </div>
</div>
@endif

<div class="receipt-page">

    <div class="receipt-page-heading">
        <h1>Payment Receipt</h1>
        <p>
            View your membership transaction
            and payment information.
        </p>
    </div>

    <div class="receipt-card">

        <div class="receipt-heading">
            <h2>Membership Payment Receipt</h2>

            <p>MemberHub Membership Management System</p>

            <p>
                Generated on:
                {{ now()->format('F d, Y h:i A') }}
            </p>

            <div class="receipt-reference">
                {{ $transaction->reference_number }}
            </div>
        </div>

        <div class="receipt-section">

            <h3>Transaction Information</h3>

            <div class="receipt-information-grid">

                <div class="receipt-information-item">
                    <strong>Transaction Reference</strong>
                    <span>{{ $transaction->reference_number }}</span>
                </div>

                <div class="receipt-information-item">
                    <strong>Member Name</strong>
                    <span>
                        {{ $transaction->member?->name ?? 'Unknown' }}
                    </span>
                </div>

                <div class="receipt-information-item">
                    <strong>Membership Number</strong>
                    <span>
                        {{ $transaction->member?->membership_number ?? 'N/A' }}
                    </span>
                </div>

                <div class="receipt-information-item">
                    <strong>Membership Level</strong>
                    <span>
                        {{ $transaction->member?->level ?? 'N/A' }}
                    </span>
                </div>

                <div class="receipt-information-item">
                    <strong>Transaction Type</strong>
                    <span>{{ $transaction->transaction_type }}</span>
                </div>

                <div class="receipt-information-item">
                    <strong>Transaction Status</strong>
                    <span class="receipt-status {{ $receiptStatusClass }}">
                        {{ $transaction->status }}
                    </span>
                </div>

            </div>
        </div>

        <div class="receipt-section">

            <h3>Payment Summary</h3>

            <div class="receipt-payment-summary">

                <div class="receipt-payment-item">
                    <strong>Membership Fee</strong>
                    <span>
                        ₱{{ number_format($transaction->amount, 2) }}
                    </span>
                </div>

                <div class="receipt-payment-item">
                    <strong>Total Paid</strong>
                    <span>
                        ₱{{ number_format($totalPaid, 2) }}
                    </span>
                </div>

                <div class="receipt-payment-item balance">
                    <strong>Remaining Balance</strong>
                    <span>
                        ₱{{ number_format($balance, 2) }}
                    </span>
                </div>

            </div>
        </div>

        <div class="receipt-section">

            <h3>Payment History</h3>

            <div class="receipt-table-wrapper">

                <table class="receipt-table">

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
                                    {{ \Illuminate\Support\Carbon::parse(
                                        $payment->payment_date
                                    )->format('M d, Y') }}
                                </td>
                            </tr>

                        @empty

                            <tr>
                                <td colspan="4" class="receipt-empty">
                                    No payments recorded.
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>
        </div>

        <div class="receipt-note">
            <strong>Note:</strong>
            This receipt summarizes recorded membership
            payments. It does not replace an official
            invoice or tax receipt.
        </div>

        <div class="receipt-actions">

            <button
                type="button"
                class="receipt-btn receipt-btn-primary"
                onclick="window.print()"
            >
                Print Receipt
            </button>

            @if($isAdmin)

                <a
                    href="{{ route('transactions.show', $transaction) }}"
                    class="receipt-btn receipt-btn-secondary"
                >
                    Back to Transaction
                </a>

            @else

                <a
                    href="{{ route('user.dashboard') }}#transactions"
                    class="receipt-btn receipt-btn-secondary"
                >
                    Back to Dashboard
                </a>

            @endif

        </div>

        <div class="receipt-footer">
            MemberHub Membership Management System
        </div>

    </div>

</div>

@if($isAdmin)
    @endsection
@else
</body>
</html>
@endif
