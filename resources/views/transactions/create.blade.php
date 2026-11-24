

@extends('layouts.admin')

@section('title', 'Create Membership Transaction')

@section('page-title', 'New Membership Transaction')

@section('content')


<style>
    .transaction-create-page {
        width: 100%;
        max-width: 1100px;
        margin: 0 auto;
        padding: 32px 24px 50px;
    }

    .transaction-create-heading {
        margin-bottom: 24px;
    }

    .transaction-create-heading h1 {
        font-size: 26px;
        font-weight: 700;
        color: #28245e;
        margin: 0 0 8px;
    }

    .transaction-create-heading p {
        font-size: 14px;
        color: #756b9e;
        margin: 0;
    }

    .transaction-create-card {
        max-width: 720px;
        margin: 0 auto;
        padding: 30px;
        background: #ffffff;
        border: 1px solid #ddd6fe;
        border-radius: 14px;
        box-shadow: 0 8px 24px rgba(48, 35, 107, 0.06);
    }

    .transaction-create-card h2 {
        font-size: 21px;
        font-weight: 700;
        color: #30236b;
        margin: 0 0 10px;
    }

    .transaction-create-description {
        font-size: 14px;
        color: #756b9e;
        margin-bottom: 26px;
    }

    .transaction-form-group {
        margin-bottom: 22px;
    }

    .transaction-form-group label {
        display: block;
        font-size: 13px;
        font-weight: 600;
        color: #30236b;
        margin-bottom: 9px;
    }

    .transaction-form-group select {
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

    .transaction-form-group select:focus {
        outline: none;
        border-color: #8768ef;
        box-shadow: 0 0 0 3px rgba(135, 104, 239, 0.12);
    }

    .transaction-info-box {
        padding: 18px;
        margin: 24px 0;
        background: #f7f5ff;
        border: 1px solid #e4ddff;
        border-radius: 10px;
    }

    .transaction-info-row {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        flex-wrap: wrap;
        gap: 12px;
        padding: 10px 0;
        font-size: 14px;
    }

    .transaction-info-row + .transaction-info-row {
        border-top: 1px solid #e4ddff;
    }

    .transaction-info-row strong {
        color: #50429a;
    }

    .transaction-info-row span {
        color: #28245e;
        font-weight: 600;
    }

    .transaction-pending-badge {
        display: inline-block;
        padding: 6px 12px;
        background: #fef3c7;
        color: #92400e !important;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }

    .transaction-form-actions {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 10px;
        margin-top: 26px;
    }

    .transaction-create-page .transaction-action-btn {
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
    }

    .transaction-create-page .transaction-action-primary {
        background: #8768ef;
        color: #ffffff;
    }

    .transaction-create-page .transaction-action-primary:hover {
        background: #7053d6;
    }

    .transaction-create-page .transaction-action-secondary {
        background: #495879;
        color: #ffffff;
    }

    .transaction-create-page .transaction-action-secondary:hover {
        background: #354361;
    }

    .transaction-create-error {
        padding: 14px 16px;
        margin-bottom: 20px;
        background: #fee2e2;
        color: #991b1b;
        border-radius: 8px;
        font-size: 13px;
    }

    .transaction-create-error ul {
        margin: 10px 0 0;
        padding-left: 20px;
    }

    .transaction-field-error {
        margin-top: 7px;
        color: #b91c1c;
        font-size: 12px;
    }

    @media (max-width: 768px) {
        .transaction-create-page {
            padding: 20px 14px 35px;
        }

        .transaction-create-card {
            padding: 20px;
        }

        .transaction-create-heading h1 {
            font-size: 22px;
        }
    }
</style>

<div class="transaction-create-page">

    <div class="transaction-create-heading">
        <h1>New Membership Transaction</h1>
        <p>Create a new membership or renewal transaction.</p>
    </div>

    <div class="transaction-create-card">

        <h2>Transaction Information</h2>

        <p class="transaction-create-description">
            Select a registered member and transaction type
            to create a membership transaction.
        </p>

        @if(session('error'))
            <div class="transaction-create-error" role="alert">
                {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div class="transaction-create-error" role="alert">
                <strong>Please correct the following errors:</strong>

                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('transactions.store') }}"
              method="POST">

            @csrf

            <div class="transaction-form-group">

                <label for="member_id">
                    Select Member
                </label>

                <select id="member_id"
                        name="member_id"
                        required>

                    <option value="">
                        Choose a registered member
                    </option>

                    @foreach($members as $member)

                        <option
                            value="{{ $member->id }}"
                            data-level="{{ $member->level }}"
                            data-fee="{{ $member->membershipLevel?->fee ?? '' }}"
                            @selected(old('member_id') == $member->id)
                        >
                            {{ $member->name }}
                            — {{ $member->membership_number }}
                            ({{ $member->level }})
                        </option>

                    @endforeach

                </select>

                @error('member_id')
                    <div class="transaction-field-error">
                        {{ $message }}
                    </div>
                @enderror

            </div>

            <div class="transaction-form-group">

                <label for="transaction_type">
                    Transaction Type
                </label>

                <select id="transaction_type"
                        name="transaction_type"
                        required>

                    <option value="">
                        Select transaction type
                    </option>

                    <option value="New Membership"
                        @selected(old('transaction_type') === 'New Membership')>
                        New Membership
                    </option>

                    <option value="Renewal"
                        @selected(old('transaction_type') === 'Renewal')>
                        Membership Renewal
                    </option>

                </select>

                @error('transaction_type')
                    <div class="transaction-field-error">
                        {{ $message }}
                    </div>
                @enderror

            </div>

            <div class="transaction-info-box">

                <div class="transaction-info-row">
                    <strong>Membership Level</strong>

                    <span id="selected-level">
                        Select a member first
                    </span>
                </div>

                <div class="transaction-info-row">
                    <strong>Membership Fee</strong>

                    <span id="selected-fee">
                        Calculated automatically
                    </span>
                </div>

                <div class="transaction-info-row">
                    <strong>Initial Status</strong>

                    <span class="transaction-pending-badge">
                        Pending
                    </span>
                </div>

            </div>

            <div class="transaction-form-actions">

                <button type="submit"
                        class="transaction-action-btn transaction-action-primary">
                    Create Transaction
                </button>

                <a href="{{ route('transactions.index') }}"
                   class="transaction-action-btn transaction-action-secondary">
                    Cancel
                </a>

            </div>

        </form>

    </div>

</div>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {

        const memberSelect = document.getElementById('member_id');
        const levelDisplay = document.getElementById('selected-level');
        const feeDisplay = document.getElementById('selected-fee');

        if (!memberSelect || !levelDisplay || !feeDisplay) {
            return;
        }

        function updateMembershipDetails() {

            const selectedOption =
                memberSelect.options[memberSelect.selectedIndex];

            if (!selectedOption || !selectedOption.value) {
                levelDisplay.textContent = 'Select a member first';
                feeDisplay.textContent = 'Calculated automatically';
                return;
            }

            const level = selectedOption.dataset.level;
            const fee = selectedOption.dataset.fee;

            levelDisplay.textContent = level || 'Not available';

            if (fee !== undefined && fee !== '') {
                const formattedFee = Number(fee).toLocaleString('en-PH', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });

                feeDisplay.textContent = '₱' + formattedFee;
            } else {
                feeDisplay.textContent = 'Fee unavailable';
            }
        }

        memberSelect.addEventListener(
            'change',
            updateMembershipDetails
        );

        updateMembershipDetails();
    });
</script>
@endpush
