
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MemberHub | User Dashboard</title>

    <style>
        * {
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f7f5ff;
            color: #30236b;
        }

        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            width: 255px;
            background: linear-gradient(180deg, #30236b, #49358f);
            color: white;
            padding: 28px 17px;
            display: flex;
            flex-direction: column;
            overflow-y: auto;
            z-index: 100;
        }

        .brand {
            font-size: 24px;
            font-weight: 800;
            padding: 0 13px 25px;
            border-bottom: 1px solid #6755a2;
            margin-bottom: 25px;
        }

        .sidebar-label {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: #c8bdf4;
            padding: 0 13px;
            margin-bottom: 15px;
        }

        .sidebar-link {
            display: block;
            padding: 14px 15px;
            margin-bottom: 8px;
            border-radius: 10px;
            text-decoration: none;
            color: #f3efff;
            font-size: 14px;
            font-weight: 600;
            transition: background .2s;
        }

        .sidebar-link:hover,
        .sidebar-link.active {
            background: #755bd5;
            color: white;
        }

        .sidebar-bottom {
            margin-top: auto;
            padding-top: 20px;
            border-top: 1px solid #6755a2;
        }

        .sidebar-bottom form {
            margin: 0;
        }

        .logout-btn {
            width: 100%;
            padding: 13px;
            border: 1px solid #8271bd;
            background: transparent;
            color: white;
            border-radius: 10px;
            cursor: pointer;
            font-weight: 600;
        }

        .logout-btn:hover {
            background: #755bd5;
        }

        .main-area {
            margin-left: 255px;
            min-height: 100vh;
        }

        .topbar {
            background: white;
            border-bottom: 1px solid #e6dfff;
            padding: 22px 35px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
        }

        .topbar-title {
            font-size: 17px;
            font-weight: 800;
        }

        .topbar-user {
            color: #71658e;
            font-size: 13px;
        }

        .content {
            max-width: 1200px;
            margin: auto;
            padding: 30px 35px 55px;
        }

        .welcome {
            background: linear-gradient(110deg, #30236b, #8065e8);
            border-radius: 17px;
            color: white;
            padding: 35px;
            margin-bottom: 25px;
        }

        .welcome h1 {
            color: white;
            margin: 0 0 10px;
            font-size: 29px;
        }

        .welcome p {
            margin: 0;
            color: #eee8ff;
            line-height: 1.6;
        }

        .stats {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 18px;
            margin-bottom: 25px;
        }

        .stat-card,
        .panel {
            background: white;
            border: 1px solid #e4dcff;
            border-radius: 14px;
            box-shadow: 0 6px 20px rgba(48, 35, 107, .04);
        }

        .stat-card {
            padding: 23px;
        }

        .stat-label {
            font-size: 13px;
            color: #756b92;
            margin-bottom: 12px;
        }

        .stat-value {
            font-size: 22px;
            font-weight: 800;
            overflow-wrap: anywhere;
        }

        .panel {
            padding: 26px;
            margin-bottom: 23px;
            scroll-margin-top: 20px;
        }

        .panel h2 {
            font-size: 20px;
            margin: 0 0 18px;
        }

        .panel-description {
            color: #756b92;
            font-size: 14px;
            line-height: 1.6;
        }

        .details {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 22px;
        }

        .detail-label {
            display: block;
            color: #756b92;
            font-size: 12px;
            margin-bottom: 9px;
        }

        .detail-value {
            font-size: 14px;
            font-weight: 600;
            overflow-wrap: anywhere;
        }

        .table-wrap {
            width: 100%;
            overflow-x: auto;
            margin-top: 18px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 630px;
        }

        th {
            background: #ede9fe;
            color: #30236b;
            padding: 15px;
            text-align: left;
            font-size: 12px;
        }

        td {
            padding: 15px;
            font-size: 13px;
            border-bottom: 1px solid #eee8ff;
            overflow-wrap: anywhere;
        }

        tr:hover td {
            background: #faf8ff;
        }

        .badge {
            display: inline-block;
            padding: 7px 12px;
            border-radius: 30px;
            font-size: 11px;
            font-weight: 700;
            background: #f3f4f6;
            color: #374151;
        }

        .status-approved,
        .status-active {
            background: #d1fae5;
            color: #065f46;
        }

        .status-pending {
            background: #fef3c7;
            color: #92400e;
        }

        .status-paid {
            background: #dbeafe;
            color: #1d4ed8;
        }

        .status-rejected,
        .status-cancelled,
        .status-expired {
            background: #fee2e2;
            color: #991b1b;
        }

        .empty {
            padding: 20px;
            background: #f7f5ff;
            border-radius: 9px;
            color: #756b92;
            font-size: 14px;
        }

        .notice {
            background: #d1fae5;
            color: #065f46;
            padding: 15px;
            border-radius: 9px;
            margin-bottom: 22px;
        }

        .action-btn {
            display: inline-block;
            background: #8065e8;
            color: white;
            padding: 12px 18px;
            border-radius: 9px;
            text-decoration: none;
            font-weight: 700;
            font-size: 13px;
            margin-top: 15px;
        }

        .action-btn:hover {
            background: #684bd3;
        }

        @media (max-width: 950px) {
            .sidebar {
                width: 210px;
            }

            .main-area {
                margin-left: 210px;
            }

            .stats {
                grid-template-columns: 1fr;
            }

            .details {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 650px) {
            .sidebar {
                position: relative;
                width: 100%;
                height: auto;
                padding: 18px;
            }

            .brand {
                margin-bottom: 15px;
                padding-bottom: 15px;
            }

            .sidebar-bottom {
                margin-top: 12px;
            }

            .main-area {
                margin-left: 0;
            }

            .topbar {
                padding: 18px;
            }

            .content {
                padding: 18px;
            }

            .welcome {
                padding: 25px;
            }

            .welcome h1 {
                font-size: 23px;
            }

            .details {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>

<aside class="sidebar">
    <div class="brand">MemberHub</div>

    <div class="sidebar-label">Member Workspace</div>

    <nav aria-label="Member navigation">
        <a href="{{ route('user.dashboard') }}"
           class="sidebar-link active">
            Dashboard
        </a>

        <a href="#membership" class="sidebar-link">
            My Membership
        </a>

        <a href="#applications" class="sidebar-link">
            My Applications
        </a>

        <a href="#transactions" class="sidebar-link">
            My Transactions
        </a>

        <a href="#account" class="sidebar-link">
            My Account
        </a>

        
@if(!$applications->contains(function ($application) {
    return in_array($application->status, ['pending', 'approved']);
}))
    <a href="{{ route('membership.create') }}"
       class="sidebar-link">
        Apply for Membership
    </a>
@endif

    </nav>

    <div class="sidebar-bottom">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="logout-btn">
                Logout
            </button>
        </form>
    </div>
</aside>

<div class="main-area">

    <header class="topbar">
        <div class="topbar-title">My Membership Dashboard</div>
        <div class="topbar-user">
            {{ auth()->user()->name }}
        </div>
    </header>

    <main class="content">

        <section class="welcome">
            <h1>Welcome back, {{ auth()->user()->name }}!</h1>
            <p>
                Manage your membership, applications,
                and transactions in one place.
            </p>
        </section>

        @if(session('success'))
            <div class="notice" role="status">
                {{ session('success') }}
            </div>
        @endif

        <section class="stats">
            <div class="stat-card">
                <div class="stat-label">Membership Status</div>
                <div class="stat-value">
                    {{ $member?->membership_validity ?? 'Not Yet a Member' }}
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-label">Membership Category</div>
                <div class="stat-value">
                    {{ $member?->category ?? 'Not Available' }}
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-label">Membership Level</div>
                <div class="stat-value">
                    {{ $member?->level ?? 'Not Available' }}
                </div>
            </div>
        </section>

        <section class="panel" id="membership">
            <h2>My Membership</h2>

            @if($member)
                @php
                    $latestApproved = $transactions
                        ->where('status', 'Approved')
                        ->sortByDesc('end_date')
                        ->first();
                @endphp

                <div class="details">
                    <div>
                        <span class="detail-label">Membership Number</span>
                        <span class="detail-value">
                            {{ $member->membership_number }}
                        </span>
                    </div>

                    <div>
                        <span class="detail-label">Category</span>
                        <span class="detail-value">
                            {{ $member->category }}
                        </span>
                    </div>

                    <div>
                        <span class="detail-label">Level</span>
                        <span class="detail-value">
                            {{ $member->level }}
                        </span>
                    </div>

                    <div>
                        <span class="detail-label">Member Record Status</span>
                        <span class="detail-value">
                            {{ $member->status }}
                        </span>
                    </div>

                    <div>
                        <span class="detail-label">Membership Start Date</span>
                        <span class="detail-value">
                            {{ $latestApproved?->start_date?->format('M d, Y') ?? 'Not yet approved' }}
                        </span>
                    </div>

                    <div>
                        <span class="detail-label">Membership Expiration</span>
                        <span class="detail-value">
                            {{ $latestApproved?->end_date?->format('M d, Y') ?? 'Not yet approved' }}
                        </span>
                    </div>
                </div>
            @else
                <div class="empty">
                    You do not have a linked membership record yet.
                </div>
            @endif
        </section>

        <section class="panel" id="applications">
            <h2>My Membership Applications</h2>
            <p class="panel-description">
                Track your submitted applications and their status.
            </p>

            @if($applications->isEmpty())
                <div class="empty">
                    You have not submitted any applications yet.
                </div>
            @else
                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th>Reference No.</th>
                                <th>Category</th>
                                <th>Status</th>
                                <th>Date Submitted</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($applications as $application)
                                <tr>
                                    <td>{{ $application->reference_no }}</td>
                                    <td>
                                        {{ $application->category?->name ?? 'Unavailable' }}
                                    </td>
                                    <td>
                                        <span class="badge status-{{ strtolower($application->status) }}">
                                            {{ ucfirst($application->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        {{ $application->created_at?->format('M d, Y') ?? 'N/A' }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </section>

        <section class="panel" id="transactions">
            <h2>My Transactions</h2>
            <p class="panel-description">
                View your membership transactions and payments.
            </p>

            @if($transactions->isEmpty())
                <div class="empty">
                    No membership transactions found.
                </div>
            @else
                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th>Reference No.</th>
                                <th>Type</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Total Paid</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($transactions as $transaction)
                                <tr>
                                    <td>{{ $transaction->reference_number }}</td>
                                    <td>{{ $transaction->transaction_type }}</td>
                                    <td>
                                        ₱{{ number_format($transaction->amount, 2) }}
                                    </td>
                                    <td>
                                        <span class="badge status-{{ strtolower($transaction->status) }}">
                                            {{ $transaction->status }}
                                        </span>
                                    </td>
                                    <td>
                                        ₱{{ number_format($transaction->payments->sum('amount'), 2) }}
                                    </td>
                                    <td>
                                        {{ $transaction->created_at?->format('M d, Y') ?? 'N/A' }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </section>

        <section class="panel" id="account">
            <h2>My Account</h2>

            <div class="details">
                <div>
                    <span class="detail-label">Name</span>
                    <span class="detail-value">
                        {{ auth()->user()->name }}
                    </span>
                </div>

                <div>
                    <span class="detail-label">Email</span>
                    <span class="detail-value">
                        {{ auth()->user()->email }}
                    </span>
                </div>

                <div>
                    <span class="detail-label">Email Verification</span>
                    <span class="detail-value">
                        {{ auth()->user()->hasVerifiedEmail()
                            ? 'Verified'
                            : 'Not Verified' }}
                    </span>
                </div>
            </div>
        </section>

    </main>
</div>

</body>
</html>
