
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Membership Transaction</title>

    <link rel="stylesheet" href="{{ asset('css/design.css') }}">
</head>

<body>

<header>
    <h1>New Membership Transaction</h1>
    <p>Create a new membership or renewal transaction</p>
</header>

<main class="container members-container">
    <div class="card">

        <h2>Transaction Information</h2>


@if(session('error'))
    <div class="error-box" role="alert">
        {{ session('error') }}
    </div>
@endif


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

        <form action="{{ route('transactions.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="member_id">Select Member</label>

                <select id="member_id" name="member_id" required>
                    <option value="">Choose a registered member</option>

                    @foreach ($members as $member)
                        <option value="{{ $member->id }}"
                            {{ old('member_id') == $member->id ? 'selected' : '' }}>
                            {{ $member->name }}
                            — {{ $member->membership_number }}
                            ({{ $member->level }})
                        </option>
                    @endforeach
                </select>

                @error('member_id')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="transaction_type">Transaction Type</label>

                <select id="transaction_type"
                        name="transaction_type"
                        required>
                    <option value="">Select transaction type</option>

                    <option value="New Membership"
                        {{ old('transaction_type') == 'New Membership' ? 'selected' : '' }}>
                        New Membership
                    </option>

                    <option value="Renewal"
                        {{ old('transaction_type') == 'Renewal' ? 'selected' : '' }}>
                        Membership Renewal
                    </option>
                </select>

                @error('transaction_type')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <p>
                    <strong>Membership Fee:</strong>
                    The fee will be calculated automatically
                    based on the selected member's membership level.
                </p>
            </div>

            <div class="form-group">
                <p>
                    <strong>Initial Status:</strong> Pending
                </p>
            </div>

            <button type="submit" class="btn">
                Create Transaction
            </button>

            <a href="{{ route('transactions.index') }}"
               class="btn btn-gray">
                Cancel
            </a>
        </form>

    </div>
</main>

</body>
</html>
