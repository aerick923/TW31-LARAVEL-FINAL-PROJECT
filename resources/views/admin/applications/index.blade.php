
@extends('layouts.admin')

@section('title', 'Membership Applications')
@section('page-title', 'Membership Applications')

@push('styles')
<style>
    .applications-page {
        max-width: 1200px;
        margin: 0 auto;
    }

    .applications-heading {
        margin-bottom: 25px;
    }

    .applications-heading h1 {
        font-size: 25px;
        color: #30236b;
        margin: 0 0 10px;
    }

    .applications-heading p {
        font-size: 14px;
        color: #756b92;
        margin: 0;
        line-height: 1.6;
    }

    .applications-card {
        background: #ffffff;
        border: 1px solid #e4dcff;
        border-radius: 14px;
        padding: 25px;
        box-shadow: 0 8px 25px rgba(48, 35, 107, 0.05);
    }

    .applications-card h2 {
        font-size: 20px;
        color: #30236b;
        margin: 0;
    }

    .applications-toolbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
        margin: 22px 0;
    }

    .applications-filter {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }

    .applications-filter label {
        font-size: 13px;
        font-weight: 600;
        color: #50429a;
    }

    .applications-filter select {
        padding: 10px 12px;
        border: 1px solid #ddd6fe;
        border-radius: 8px;
        background: white;
        color: #30236b;
        font-size: 13px;
    }

    .filter-btn {
        padding: 10px 15px;
        border: none;
        border-radius: 8px;
        background: #8065e8;
        color: white;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
    }

    .filter-btn:hover {
        background: #684bd3;
    }

    .clear-filter {
        padding: 10px 15px;
        border-radius: 8px;
        background: #eee9ff;
        color: #49358f;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
    }

    .applications-table-wrapper {
        width: 100%;
        overflow-x: auto;
        border: 1px solid #ddd6fe;
        border-radius: 10px;
    }

    .applications-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 1100px;
    }

    .applications-table th,
    .applications-table td {
        padding: 14px 12px;
        text-align: left;
        border-bottom: 1px solid #e9e4f8;
        font-size: 13px;
        vertical-align: middle;
    }

    .applications-table th {
        background: #ede9fe;
        color: #30236b;
        font-weight: 700;
        white-space: nowrap;
    }

    .applications-table tbody tr:hover {
        background: #faf8ff;
    }

    .applications-table tbody tr:last-child td {
        border-bottom: none;
    }

    .application-reference {
        font-weight: 600;
        color: #30236b;
        white-space: nowrap;
    }

    .application-email {
        overflow-wrap: anywhere;
    }

    .status-badge {
        display: inline-block;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 700;
        white-space: nowrap;
    }

    .status-pending {
        background: #fef3c7;
        color: #92400e;
    }

    .status-approved {
        background: #d1fae5;
        color: #065f46;
    }

    .status-rejected {
        background: #fee2e2;
        color: #991b1b;
    }

    .level-unavailable {
        color: #b45309;
        font-size: 12px;
        font-weight: 600;
    }

    .review-actions {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
    }

    .review-actions form {
        margin: 0;
    }

    .approve-btn,
    .reject-btn {
        padding: 9px 13px;
        border: none;
        border-radius: 7px;
        font-size: 12px;
        font-weight: 700;
        cursor: pointer;
        color: white;
    }

    .approve-btn {
        background: #15803d;
    }

    .approve-btn:hover {
        background: #166534;
    }

    .reject-btn {
        background: #dc2626;
    }

    .reject-btn:hover {
        background: #b91c1c;
    }

    .review-completed {
        color: #6b7280;
        font-size: 12px;
        font-weight: 600;
    }

    .review-alert-success {
        background: #d1fae5;
        color: #065f46;
        padding: 15px;
        border-radius: 9px;
        margin: 20px 0;
        font-size: 13px;
    }

    .review-alert-error {
        background: #fee2e2;
        color: #991b1b;
        padding: 15px;
        border-radius: 9px;
        margin: 20px 0;
        font-size: 13px;
    }

    .review-alert-error ul {
        margin: 8px 0 0;
        padding-left: 20px;
    }

    .empty-message {
        padding: 30px;
        background: #f7f5ff;
        border-radius: 9px;
        text-align: center;
        color: #756b92;
        font-size: 14px;
    }

    .applications-pagination {
        margin-top: 25px;
    }

    @media (max-width: 768px) {
        .applications-card {
            padding: 18px;
        }

        .applications-heading h1 {
            font-size: 22px;
        }

        .applications-toolbar {
            align-items: flex-start;
        }
    }
</style>
@endpush

@section('content')

<div class="applications-page">

    <div class="applications-heading">
        <h1>Membership Applications</h1>
        <p>
            Review submitted membership applications,
            verify their selected categories and levels,
            and manage approval decisions.
        </p>
    </div>

    <div class="applications-card">

        <h2>Application Management</h2>

        @if(session('success'))
            <div class="review-alert-success" role="status">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="review-alert-error" role="alert">
                {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div class="review-alert-error" role="alert">
                <strong>Please review the following:</strong>

                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="applications-toolbar">

            <p style="font-size: 13px; color: #756b92;">
                Total Applications:
                <strong>{{ $applications->total() }}</strong>
            </p>

            <form method="GET"
                  action="{{ route('admin.applications.index') }}"
                  class="applications-filter">

                <label for="status">Filter by Status:</label>

                <select name="status" id="status">
                    <option value="">All Applications</option>

                    <option value="pending"
                        @selected(request('status') === 'pending')>
                        Pending
                    </option>

                    <option value="approved"
                        @selected(request('status') === 'approved')>
                        Approved
                    </option>

                    <option value="rejected"
                        @selected(request('status') === 'rejected')>
                        Rejected
                    </option>
                </select>

                <button type="submit" class="filter-btn">
                    Apply Filter
                </button>

                <a href="{{ route('admin.applications.index') }}"
                   class="clear-filter">
                    Clear
                </a>

            </form>
        </div>

        @if($applications->isEmpty())

            <div class="empty-message">
                No membership applications found.
            </div>

        @else

            <div class="applications-table-wrapper">

                <table class="applications-table">

                    <thead>
                        <tr>
                            <th>Reference No.</th>
                            <th>Applicant</th>
                            <th>Email</th>
                            <th>Category</th>
                            <th>Level</th>
                            <th>Membership Fee</th>
                            <th>Status</th>
                            <th>Date Submitted</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($applications as $application)

                            <tr>

                                <td class="application-reference">
                                    {{ $application->reference_no }}
                                </td>

                                <td>
                                    {{ $application->full_name }}
                                </td>

                                <td class="application-email">
                                    {{ $application->email }}
                                </td>

                                <td>
                                    {{ $application->category?->name ?? 'Unavailable' }}
                                </td>

                                <td>
                                    @if($application->membershipLevel)
                                        {{ $application->membershipLevel->name }}
                                    @else
                                        <span class="level-unavailable">
                                            Not Selected
                                        </span>
                                    @endif
                                </td>

                                <td>
                                    @if($application->membershipLevel)
                                        @if($application->membershipLevel->fee !== null)
                                            ₱{{ number_format($application->membershipLevel->fee, 2) }}
                                        @else
                                            Not specified
                                        @endif
                                    @else
                                        <span class="level-unavailable">
                                            Unavailable
                                        </span>
                                    @endif
                                </td>

                                <td>
                                    <span class="status-badge status-{{ $application->status }}">
                                        {{ ucfirst($application->status) }}
                                    </span>
                                </td>

                                <td>
                                    {{ $application->created_at?->format('M d, Y') ?? 'N/A' }}
                                </td>

                                <td>

                                    @if($application->status === 'pending')

                                        <div class="review-actions">

                                            @if($application->membershipLevel)

                                                <form method="POST"
                                                      action="{{ route('admin.applications.update-status', $application) }}"
                                                      onsubmit="return confirm('Are you sure you want to approve this application?');">

                                                    @csrf
                                                    @method('PATCH')

                                                    <input type="hidden"
                                                           name="status"
                                                           value="approved">

                                                    <button type="submit"
                                                            class="approve-btn">
                                                        Approve
                                                    </button>

                                                </form>

                                            @else

                                                <span class="level-unavailable"
                                                      title="This application has no selected membership level.">
                                                    Approval Unavailable
                                                </span>

                                            @endif

                                            <form method="POST"
                                                  action="{{ route('admin.applications.update-status', $application) }}"
                                                  onsubmit="return confirm('Are you sure you want to reject this application?');">

                                                @csrf
                                                @method('PATCH')

                                                <input type="hidden"
                                                       name="status"
                                                       value="rejected">

                                                <button type="submit"
                                                        class="reject-btn">
                                                    Reject
                                                </button>

                                            </form>

                                        </div>

                                    @else

                                        <span class="review-completed">
                                            Reviewed
                                        </span>

                                    @endif

                                </td>

                            </tr>

                        @endforeach
                    </tbody>

                </table>

            </div>

            <div class="applications-pagination">
                {{ $applications->links() }}
            </div>

        @endif

    </div>
</div>

@endsection
