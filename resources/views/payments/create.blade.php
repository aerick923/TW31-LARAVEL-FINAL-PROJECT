
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Record Payment</title>

    <link rel="stylesheet" href="{{ asset('css/design.css') }}">
</head>

<body>

<header>
    <h1>Record Payment</h1>
    <p>Record a payment for a membership transaction</p>
</header>

<main class="container members-container">
    <div class="card">

        <h2>Payment Information</h2>

        @if ($errors->any())
            <div class="error-box" role="alert">
                <strong>Please correct the following errors:</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="detail">
            <strong>Transaction Reference</strong>
            <span>{{ $transaction->reference_number }}</span>
        </div>

        <div class="detail">
            <strong>Member</strong>
            <span>{{ $transaction->member->name }}</span>
        </div>

        <div class="detail">
            <strong>Membership Fee</strong>
            <span>
                ₱{{ number_format($transaction->amount, 2) }}
            </span>
        </div>

        <div class="detail">
            <strong>Total Paid</strong>
            <span>
                ₱{{ number_format($totalPaid, 2) }}
            </span>
        </div>

        <div class="detail">
            <strong>Remaining Balance</strong>
            <span>
                ₱{{ number_format($balance, 2) }}
            </span>
        </div>

        @if($transaction->status === 'Pending' && $balance > 0)

            <form action="{{ route('payments.store', $transaction) }}"
                  method="POST">

                @csrf

                <div class="form-group">
                    <label for="amount">Payment Amount (₱)</label>

                    <input type="number"
                           id="amount"
                           name="amount"
                           min="0.01"
                           max="{{ number_format($balance, 2, '.', '') }}"
                           step="0.01"
                           value="{{ old('amount') }}"
                           required>

                    @error('amount')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="payment_method">Payment Method</label>

                    <select id="payment_method"
                            name="payment_method"
                            required>

                        <option value="">Select payment method</option>

                        @foreach(['Cash', 'GCash', 'Bank Transfer'] as $method)
                            <option value="{{ $method }}"
                                {{ old('payment_method') === $method ? 'selected' : '' }}>
                                {{ $method }}
                            </option>
                        @endforeach
                    </select>

                    @error('payment_method')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="payment_date">Payment Date</label>

                    <input type="date"
                           id="payment_date"
                           name="payment_date"
                           max="{{ now()->toDateString() }}"
                           value="{{ old('payment_date', now()->toDateString()) }}"
                           required>

                    @error('payment_date')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-green">
                    Record Payment
                </button>

                <a href="{{ route('transactions.show', $transaction) }}"
                   class="btn btn-gray">
                    Cancel
                </a>
            </form>

        @else

            <div class="error-box">
                This transaction cannot accept additional payments.
            </div>

            <a href="{{ route('transactions.show', $transaction) }}"
               class="btn btn-gray">
                Back to Transaction
            </a>

        @endif

    </div>
</main>

</body>
</html>
