
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Member Profile</title>

    <link rel="stylesheet" href="{{ asset('css/design.css') }}">
</head>

<body>

<header>
    <h1>Member Profile</h1>
    <p>View complete membership information</p>
</header>

<main class="profile-container">
    <div class="card">

        <h2>{{ $member->name }}</h2>

        <div class="detail">
            <strong>Membership Number</strong>
            <span>{{ $member->membership_number }}</span>
        </div>

        <div class="detail">
            <strong>Full Name</strong>
            <span>{{ $member->name }}</span>
        </div>

        <div class="detail">
            <strong>Email Address</strong>
            <span>{{ $member->email }}</span>
        </div>

        <div class="detail">
            <strong>Phone Number</strong>
            <span>{{ $member->phone ?: 'Not provided' }}</span>
        </div>

        <div class="detail">
            <strong>Membership Category</strong>
            <span>{{ $member->category }}</span>
        </div>

        <div class="detail">
            <strong>Membership Level</strong>
            <span>{{ $member->level }}</span>
        </div>

        <div class="detail">
            <strong>Membership Fee</strong>

            @if($member->membershipLevel)
                <span>
                    ₱{{ number_format($member->membershipLevel->fee, 2) }}
                </span>
            @else
                <span>Fee unavailable</span>
            @endif
        </div>

        <div class="detail">
            <strong>Membership Validity Period</strong>

            @if($member->membershipLevel?->validity_months)
                <span>
                    {{ $member->membershipLevel->validity_months }} Months
                </span>
            @else
                <span>Validity period unavailable</span>
            @endif
        </div>

        <div class="detail">
            <strong>Membership Benefits</strong>

            @if($member->membershipLevel?->benefits)
                <span>{{ $member->membershipLevel->benefits }}</span>
            @else
                <span>No benefits specified</span>
            @endif
        </div>

        <div class="detail">
            <strong>Membership Status</strong>
            <span>{{ $member->status }}</span>
        </div>

        <div class="detail">
            <strong>Date Registered</strong>
            <span>{{ $member->created_at->format('F d, Y') }}</span>
        </div>

        <div class="profile-actions">
            <a href="{{ route('members.edit', $member) }}"
               class="btn btn-orange">
                Edit Member
            </a>

            <a href="{{ route('members.index') }}"
               class="btn btn-gray">
                Back to Members
            </a>
        </div>

    </div>
</main>

</body>
</html>