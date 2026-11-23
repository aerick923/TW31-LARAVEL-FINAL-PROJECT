
@extends('layouts.admin')

@section('title', 'Administrator Dashboard')

@section('page-title', 'Dashboard Overview')

@push('styles')
<style>
    .dashboard-grid {
        display: grid;
        grid-template-columns:
            repeat(auto-fit, minmax(210px, 1fr));
        gap: 20px;
        margin: 25px 0;
    }

    .dashboard-stat {
        background: #ffffff;
        border: 1px solid #dcd3ff;
        border-radius: 12px;
        padding: 24px;
    }

    .dashboard-stat h3 {
        font-size: 15px;
        color: #50429a;
        margin-bottom: 12px;
    }

    .dashboard-stat .stat-value {
        font-size: 30px;
        font-weight: 700;
        color: #28245e;
    }

    .dashboard-section {
        margin-top: 30px;
    }

    .dashboard-section h2 {
        margin-bottom: 15px;
    }

    .dashboard-table-card {
        background: white;
        border: 1px solid #dcd3ff;
        border-radius: 12px;
        padding: 25px;
    }

    .dashboard-table-card .table-wrapper {
        overflow-x: auto;
    }
</style>
@endpush

@section('content')

    <h2>Dashboard Overview</h2>

    <p>
        Welcome, {{ auth()->user()->name }}!
    </p>

    <!-- DASHBOARD STATISTICS -->

    <div class="dashboard-grid">

        <div class="dashboard-stat">
            <h3>Total Registered Members</h3>

            <div class="stat-value">
                {{ number_format($totalMembers) }}
            </div>
        </div>

        <div class="dashboard-stat">
            <h3>Active Members</h3>

            <div class="stat-value">
                {{ number_format($activeMembers) }}
            </div>
        </div>

        <div class="dashboard-stat">
            <h3>Transactions Awaiting Processing</h3>

            <div class="stat-value">
                {{ number_format($pendingTransactions) }}
            </div>
        </div>

        <div class="dashboard-stat">
            <h3>Total Payments Collected</h3>

            <div class="stat-value">
                ₱{{ number_format($totalPayments, 2) }}
            </div>
        </div>

    </div>

    <!-- RECENT TRANSACTIONS -->

    <div class="dashboard-section">

        <div class="dashboard-table-card">

            <h2>Recent Membership Transactions</h2>

            <div class="table-wrapper">

                <table>
                    <thead>
                        <tr>
                            <th>Reference No.</th>
                            <th>Member</th>
                            <th>Type</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($recentTransactions as $transaction)
                            <tr>
                                <td>
                                    {{ $transaction->reference_number }}
                                </td>

                                <td>
                                    {{ $transaction->member?->name ?? 'Unknown' }}
                                </td>

                                <td>
                                    {{ $transaction->transaction_type }}
                                </td>

                                <td>
                                    ₱{{ number_format($transaction->amount, 2) }}
                                </td>

                                <td>
                                    {{ $transaction->status }}
                                </td>

                                <td>
                                    <a href="{{ route('transactions.show', $transaction) }}"
                                       class="btn">
                                        View
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6">
                                    No membership transactions found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

            </div>

        </div>

    </div>

@endsection
