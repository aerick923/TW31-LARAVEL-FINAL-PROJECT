
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>My Membership | MemberHub</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f7f5ff;
            color: #30236b;
        }

        .navbar {
            background: white;
            border-bottom: 1px solid #e6dfff;
            padding: 18px 30px;
        }

        .navbar-inner {
            max-width: 1100px;
            margin: auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .brand {
            font-size: 21px;
            font-weight: bold;
            color: #30236b;
            text-decoration: none;
        }

        .container {
            max-width: 1000px;
            margin: 50px auto;
            padding: 0 20px;
        }

        .page-heading {
            margin-bottom: 30px;
        }

        .page-heading h1 {
            font-size: 32px;
            margin-bottom: 10px;
        }

        .page-heading p {
            color: #756b92;
        }

        .card {
            background: white;
            border: 1px solid #e4dcff;
            border-radius: 16px;
            padding: 30px;
            box-shadow: 0 8px 25px rgba(48, 35, 107, 0.05);
        }

        .details-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }

        .detail {
            padding: 20px;
            border: 1px solid #eee8ff;
            border-radius: 12px;
            background: #faf9ff;
        }

        .detail-label {
            font-size: 13px;
            color: #756b92;
            margin-bottom: 10px;
        }

        .detail-value {
            font-size: 17px;
            font-weight: bold;
            overflow-wrap: anywhere;
        }

        .badge {
            display: inline-block;
            padding: 8px 14px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: bold;
        }

        .badge-active {
            background: #d1fae5;
            color: #065f46;
        }

        .badge-inactive {
            background: #fee2e2;
            color: #991b1b;
        }

        .actions {
            margin-top: 30px;
        }

        .btn {
            display: inline-block;
            background: #8065e8;
            color: white;
            text-decoration: none;
            padding: 13px 20px;
            border-radius: 9px;
            font-weight: bold;
            font-size: 14px;
        }

        .btn:hover {
            background: #684bd3;
        }

        .empty-state {
            padding: 25px;
            background: #f5f3ff;
            border-radius: 10px;
            color: #756b92;
        }

        @media (max-width: 650px) {
            .details-grid {
                grid-template-columns: 1fr;
            }

            .container {
                margin-top: 25px;
            }

            .card {
                padding: 20px;
            }
        }
    </style>
</head>

<body>

<header class="navbar">
    <div class="navbar-inner">
        <a href="{{ route('user.dashboard') }}"
           class="brand">
            MemberHub
        </a>

        
    </div>
</header>

<main class="container">

    <div class="page-heading">
        <h1>My Membership</h1>
        <p>
            View your membership information
            and validity period.
        </p>
    </div>

    <div class="card">

        @if($member)

            @php
                $membershipStatus =
                    $member->membership_validity ?? 'Inactive';
            @endphp

            <div class="details-grid">

                <div class="detail">
                    <div class="detail-label">
                        Membership Number
                    </div>
                    <div class="detail-value">
                        {{ $member->membership_number }}
                    </div>
                </div>

                <div class="detail">
                    <div class="detail-label">
                        Full Name
                    </div>
                    <div class="detail-value">
                        {{ $member->name }}
                    </div>
                </div>

                <div class="detail">
                    <div class="detail-label">
                        Membership Category
                    </div>
                    <div class="detail-value">
                        {{ $member->category }}
                    </div>
                </div>

                <div class="detail">
                    <div class="detail-label">
                        Membership Level
                    </div>
                    <div class="detail-value">
                        {{ $member->level }}
                    </div>
                </div>

                <div class="detail">
                    <div class="detail-label">
                        Membership Status
                    </div>
                    <div class="detail-value">
                        <span class="badge
                            {{ $membershipStatus === 'Active'
                                ? 'badge-active'
                                : 'badge-inactive' }}">
                            {{ $membershipStatus }}
                        </span>
                    </div>
                </div>

                <div class="detail">
                    <div class="detail-label">
                        Member Record Status
                    </div>
                    <div class="detail-value">
                        {{ $member->status }}
                    </div>
                </div>

                <div class="detail">
                    <div class="detail-label">
                        Membership Start Date
                    </div>
                    <div class="detail-value">
                        {{ $latestTransaction?->start_date
                            ?->format('F d, Y') ?? 'Not available' }}
                    </div>
                </div>

                <div class="detail">
                    <div class="detail-label">
                        Membership Expiration Date
                    </div>
                    <div class="detail-value">
                        {{ $latestTransaction?->end_date
                            ?->format('F d, Y') ?? 'Not available' }}
                    </div>
                </div>

            </div>

        @else

            <div class="empty-state">
                Your membership has not been approved yet.
            </div>

        @endif

        <div class="actions">
            <a href="{{ route('user.dashboard') }}"
               class="btn">
                Back to Dashboard
            </a>
        </div>

    </div>

</main>

</body>
</html>
