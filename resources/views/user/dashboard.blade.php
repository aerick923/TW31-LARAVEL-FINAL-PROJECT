
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
            margin-left: 0px;
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


/* My Account sidebar button */
.account-trigger {
    width: 100%;
    border: none;
    background: transparent;
    text-align: left;
    font-family: inherit;
    cursor: pointer;
}

/* Popup background */
.account-modal-overlay {
    position: fixed;
    inset: 0;
    background: rgba(20, 15, 48, 0.55);
    z-index: 1000;
    display: none;
    align-items: center;
    justify-content: center;
    padding: 20px;
}

/* Show popup */
.account-modal-overlay.is-open {
    display: flex;
}

/* Popup container */
.account-modal {
    background: white;
    border-radius: 18px;
    padding: 30px;
    width: 100%;
    max-width: 480px;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2);
    color: #30236b;
}

/* Popup heading */
.account-modal-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 25px;
}

.account-modal-header h2 {
    margin: 0;
    font-size: 23px;
}

/* Close button */
.account-modal-close {
    border: none;
    background: #f0ebff;
    color: #30236b;
    width: 36px;
    height: 36px;
    border-radius: 50%;
    cursor: pointer;
    font-size: 23px;
}

.account-modal-close:hover {
    background: #ddd3ff;
}

/* Account details */
.account-modal-detail {
    padding: 15px 0;
    border-bottom: 1px solid #eee8ff;
}

.account-modal-detail:last-child {
    border-bottom: none;
}

.account-modal-detail .detail-value {
    display: block;
    margin-top: 5px;
}

.account-modal-detail .detail-label {
    margin-bottom: 5px;
}

body.account-modal-open {
    overflow: hidden;
}


/* MEMBERHUB TOP NAVIGATION */

.member-navbar {
    background: #ffffff;
    border-bottom: 1px solid #e6dfff;
    width: 100%;
    position: relative;
    z-index: 100;
}

.navbar-container {
    max-width: 1250px;
    margin: 0 auto;
    padding: 18px 30px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 25px;
}

.navbar-brand {
    display: flex;
    align-items: center;
    gap: 12px;
    text-decoration: none;
    color: #30236b;
}

.brand-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 42px;
    height: 42px;
    border-radius: 12px;
    background: linear-gradient(135deg, #30236b, #8065e8);
    color: white;
    font-size: 22px;
    font-weight: 800;
}

.navbar-brand strong {
    display: block;
    font-size: 19px;
}

.navbar-brand small {
    display: block;
    margin-top: 3px;
    font-size: 11px;
    color: #756b92;
}

.navbar-actions {
    display: flex;
    align-items: center;
    gap: 12px;
    flex-wrap: wrap;
}

.navbar-link,
.navbar-apply,
.navbar-logout {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 11px 15px;
    border-radius: 9px;
    text-decoration: none;
    font-size: 13px;
    font-weight: 700;
    font-family: inherit;
    cursor: pointer;
}

.navbar-link {
    color: #49358f;
    background: #f3efff;
}

.navbar-link:hover {
    background: #e6dfff;
}

.navbar-button {
    border: none;
}

.navbar-apply {
    color: white;
    background: #8065e8;
}

.navbar-apply:hover {
    background: #684bd3;
}

.navbar-logout-form {
    margin: 0;
}

.navbar-logout {
    border: 1px solid #ddd6fe;
    background: white;
    color: #49358f;
}

.navbar-logout:hover {
    background: #f3efff;
}

@media (max-width: 700px) {
    .navbar-container {
        flex-direction: column;
        align-items: stretch;
        padding: 15px 18px;
    }

    .navbar-actions {
        justify-content: flex-start;
        gap: 8px;
    }

    .navbar-link,
    .navbar-apply,
    .navbar-logout {
        font-size: 12px;
        padding: 10px 12px;
    }
}



/* EXPANDABLE DASHBOARD CARDS */

.dashboard-card {
    background: white;
    border: 1px solid #e4dcff;
    border-radius: 16px;
    margin-bottom: 20px;
    box-shadow: 0 6px 20px rgba(48, 35, 107, 0.04);
    overflow: hidden;
}

.dashboard-card summary {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
    padding: 25px;
    cursor: pointer;
    list-style: none;
}

.dashboard-card summary::-webkit-details-marker {
    display: none;
}

.dashboard-card summary:hover {
    background: #faf8ff;
}

.dashboard-card-title {
    font-size: 19px;
    font-weight: 800;
    color: #30236b;
    margin-bottom: 8px;
}

.dashboard-card-description {
    font-size: 13px;
    color: #756b92;
    line-height: 1.6;
}

.dashboard-card-toggle {
    background: #ede9fe;
    color: #49358f;
    padding: 10px 15px;
    border-radius: 9px;
    font-size: 12px;
    font-weight: 700;
    white-space: nowrap;
}

.dashboard-card[open] .dashboard-card-toggle {
    background: #8065e8;
    color: white;
}

.dashboard-card-content {
    padding: 25px;
    border-top: 1px solid #eee8ff;
}

@media (max-width: 650px) {
    .dashboard-card summary {
        padding: 18px;
    }

    .dashboard-card-content {
        padding: 18px;
    }

    .dashboard-card-title {
        font-size: 17px;
    }
}


/* Separate page navigation cards */

.dashboard-card-summary {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
    padding: 25px;
}

.dashboard-card-link {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    background: #ede9fe;
    color: #49358f;
    transition: background 0.2s ease;
}

.dashboard-card-link:hover {
    background: #8065e8;
    color: white;
}

.dashboard-card-link:focus-visible {
    outline: 3px solid #8065e8;
    outline-offset: 3px;
}

@media (max-width: 650px) {
    .dashboard-card-summary {
        flex-direction: column;
        align-items: flex-start;
        padding: 18px;
    }
}



    </style>
</head>

<body>


<!-- MEMBERHUB TOP NAVIGATION -->
<header class="member-navbar">
    <div class="navbar-container">

        <a href="{{ route('user.dashboard') }}"
           class="navbar-brand">
            <span class="brand-icon">M</span>

            <span>
                <strong>MemberHub</strong>
                <small>Membership Portal</small>
            </span>
        </a>

        <nav class="navbar-actions"
             aria-label="Member navigation">

            <a href="{{ route('user.dashboard') }}"
               class="navbar-link">
                Dashboard
            </a>

            @if(!$applications->contains(function ($application) {
                return in_array($application->status, ['pending', 'approved']);
            }))
                <a href="{{ route('membership.create') }}"
                   class="navbar-apply">
                    Apply for Membership
                </a>
            @endif

            <!-- Preserve My Account popup -->
            <button
                type="button"
                id="openAccountModal"
                class="navbar-link navbar-button"
                aria-haspopup="dialog"
                aria-controls="accountModal"
            >
                My Account
            </button>

            <form action="{{ route('logout') }}"
                  method="POST"
                  class="navbar-logout-form">
                @csrf

                <button type="submit"
                        class="navbar-logout">
                    Logout
                </button>
            </form>

        </nav>
    </div>
</header>


<div class="main-area">

   

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

<!-- MY MEMBERSHIP CARD -->
<section class="dashboard-card" id="membership">

    <div class="dashboard-card-summary">

        <div>
            <div class="dashboard-card-title">
                My Membership
            </div>

            <div class="dashboard-card-description">
                View your membership number, category,
                level, and validity period.
            </div>
        </div>

        <a href="{{ route('user.membership') }}"
           class="dashboard-card-toggle dashboard-card-link">
            View Membership
        </a>

        </div>

    </section>
  

<!-- MY APPLICATIONS CARD -->

<!-- MY APPLICATION CARD -->
<section class="dashboard-card" id="applications">

    <div class="dashboard-card-summary">

        <div>
            <div class="dashboard-card-title">
                My Application
            </div>

            <div class="dashboard-card-description">
                Track the status of your membership application.
            </div>
        </div>

        <a href="{{ route('user.applications') }}"
           class="dashboard-card-toggle dashboard-card-link">
            View Application
        </a>

    </div>

</section>


<!-- MY TRANSACTIONS CARD -->
<section class="dashboard-card" id="transactions">

    <div class="dashboard-card-summary">

        <div>
            <div class="dashboard-card-title">
                My Transactions
            </div>

            <div class="dashboard-card-description">
                View your transaction history, payment records,
                and printable receipts.
            </div>
        </div>

        <a href="{{ route('user.transactions') }}"
           class="dashboard-card-toggle dashboard-card-link">
            View Transactions
        </a>

    </div>

</section>



<!-- MY TASKS CARD -->
<section class="dashboard-card" id="tasks">

    <div class="dashboard-card-summary">

        <div>
            <div class="dashboard-card-title">
                My Tasks
            </div>

            <div class="dashboard-card-description">
                View tasks assigned to you by the administrator
                and track your progress.
            </div>
        </div>

        <a href="{{ route('user.tasks.index') }}"
           class="dashboard-card-toggle dashboard-card-link">
            View Tasks
        </a>

    </div>

</section>




        
<!-- MY ACCOUNT POPUP -->
<div
    id="accountModal"
    class="account-modal-overlay"
    aria-hidden="true"
>
    <div
        class="account-modal"
        role="dialog"
        aria-modal="true"
        aria-labelledby="accountModalTitle"
        tabindex="-1"
    >
        <div class="account-modal-header">
            <h2 id="accountModalTitle">My Account</h2>

            <button
                type="button"
                id="closeAccountModal"
                class="account-modal-close"
                aria-label="Close My Account"
            >
                &times;
            </button>
        </div>

        <div class="account-modal-detail">
            <span class="detail-label">Full Name</span>
            <span class="detail-value">
                {{ auth()->user()->name }}
            </span>
        </div>

        <div class="account-modal-detail">
            <span class="detail-label">Email Address</span>
            <span class="detail-value">
                {{ auth()->user()->email }}
            </span>
        </div>

        <div class="account-modal-detail">
            <span class="detail-label">Email Verification</span>

            <span class="detail-value">
                @if(auth()->user()->hasVerifiedEmail())
                    <span class="badge status-approved">
                        Verified
                    </span>
                @else
                    <span class="badge status-pending">
                        Not Verified
                    </span>
                @endif
            </span>
        </div>
    </div>
</div>


    </main>
</div>


<script>
document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('accountModal');
    const openButton = document.getElementById('openAccountModal');
    const closeButton = document.getElementById('closeAccountModal');
    const dialog = modal.querySelector('.account-modal');

    function openModal() {
        modal.classList.add('is-open');
        modal.setAttribute('aria-hidden', 'false');
        document.body.classList.add('account-modal-open');
        closeButton.focus();
    }

    function closeModal() {
        modal.classList.remove('is-open');
        modal.setAttribute('aria-hidden', 'true');
        document.body.classList.remove('account-modal-open');
        openButton.focus();
    }

    openButton.addEventListener('click', openModal);
    closeButton.addEventListener('click', closeModal);

    // Close when clicking outside the popup.
    modal.addEventListener('click', function (event) {
        if (event.target === modal) {
            closeModal();
        }
    });

    // Close using Escape and keep Tab focus inside.
    document.addEventListener('keydown', function (event) {
        if (!modal.classList.contains('is-open')) {
            return;
        }

        if (event.key === 'Escape') {
            closeModal();
            return;
        }

        if (event.key === 'Tab') {
            const focusable = Array.from(
                dialog.querySelectorAll(
                    'button:not([disabled]), a[href], input:not([disabled]), select:not([disabled]), textarea:not([disabled]), [tabindex]:not([tabindex="-1"])'
                )
            );

            if (!focusable.length) {
                event.preventDefault();
                dialog.focus();
                return;
            }

            const first = focusable[0];
            const last = focusable[focusable.length - 1];

            if (event.shiftKey && document.activeElement === first) {
                event.preventDefault();
                last.focus();
            } else if (!event.shiftKey && document.activeElement === last) {
                event.preventDefault();
                first.focus();
            }
        }
    });
});
</script>


</body>
</html>
