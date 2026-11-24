
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


/* Advanced Application Search */

.applications-advanced-filter {
    width: 100%;
    background: #faf9ff;
    border: 1px solid #e1d9ff;
    border-radius: 12px;
    padding: 22px;
}

.applications-advanced-filter label {
    display: block;
    font-size: 13px;
    font-weight: 600;
    color: #30236b;
    margin-bottom: 8px;
}

.applications-advanced-filter input,
.applications-advanced-filter select {
    width: 100%;
    min-width: 0;
    padding: 12px;
    border: 1px solid #dcd3ff;
    border-radius: 8px;
    background: white;
    color: #30236b;
    font-size: 13px;
}

.application-search-field {
    margin-bottom: 18px;
}

.application-filter-grid {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 15px;
}

.application-filter-buttons {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    margin-top: 20px;
}

.applications-advanced-filter input:focus,
.applications-advanced-filter select:focus {
    outline: none;
    border-color: #8064ed;
    box-shadow: 0 0 0 3px rgba(128, 100, 237, 0.12);
}

@media (max-width: 768px) {
    .application-filter-grid {
        grid-template-columns: 1fr;
    }

    .applications-advanced-filter {
        padding: 16px;
    }
}

.applications-toolbar {
    flex-direction: column;
    align-items: stretch;
}


/* Application Review Navigation Buttons */

.applications-page .application-view-btn,
.applications-page .application-member-btn {
    display: inline-block;
    padding: 10px 13px;
    border-radius: 8px;
    font-size: 12px;
    font-weight: 600;
    text-decoration: none;
    white-space: nowrap;
    transition: background 0.2s ease;
}

.applications-page .application-view-btn {
    background: #8064ed;
    color: #ffffff;
}

.applications-page .application-view-btn:hover {
    background: #684bd3;
}

.applications-page .application-member-btn {
    background: #eee9ff;
    color: #30236b;
}

.applications-page .application-member-btn:hover {
    background: #ddd3ff;
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


<div style="margin: 20px 0;">
    <a
        href="{{ route('admin.applications.create') }}"
        style="
            display: inline-block;
            background: #398b6b;
            color: white;
            padding: 12px 18px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
        "
    >
        + Record Application
    </a>
</div>


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
      class="applications-advanced-filter">

    <div class="application-search-field">
        <label for="application-search">
            Search Applications
        </label>

        <input
            type="text"
            id="application-search"
            name="search"
            value="{{ request('search') }}"
            placeholder="Applicant name, email, or reference number"
            maxlength="255"
        >
    </div>

    <div class="application-filter-grid">

        <div>
            <label for="status">Application Status</label>

            <select name="status" id="status">
                <option value="">All Statuses</option>

                @foreach(['pending', 'approved', 'rejected'] as $status)
                    <option value="{{ $status }}"
                        @selected(request('status') === $status)>
                        {{ ucfirst($status) }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="category">Membership Category</label>

            <select name="category" id="category">
                <option value="">All Categories</option>

                @foreach($categories as $category)
                    <option value="{{ $category->id }}"
                        @selected(request('category') == $category->id)>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="level">Membership Level</label>

            <select name="level" id="level">
                <option value="">All Levels</option>

                @foreach($levels as $level)
                    <option value="{{ $level->id }}"
                        @selected(request('level') == $level->id)>
                        {{ $level->name }}
                    </option>
                @endforeach
            </select>
        </div>

    </div>

    <div class="application-filter-buttons">
        <button type="submit" class="filter-btn">
            Search Applications
        </button>

        <a href="{{ route('admin.applications.index') }}"
           class="clear-filter">
            Clear Filters
        </a>
    </div>

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
    <div class="review-actions">

        <!-- View the application before making a decision -->
        <a href="{{ route('admin.applications.show', $application) }}"
           class="application-view-btn">
            View Application
        </a>

        <!-- View the linked member profile after approval -->
        @if(
            $application->status === 'approved' &&
            $application->member_id
        )
            <a href="{{ route('members.show', $application->member_id) }}"
               class="application-member-btn">
                View Member
            </a>
        @endif

    </div>
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
