
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Membership Applications</title>

    <link rel="stylesheet"
          href="{{ asset('css/design.css') }}">

    <style>
        .applications-card {
            max-width: 1100px;
            margin: 40px auto;
        }

        .applications-toolbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
            margin: 20px 0;
        }

        .applications-filter {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .applications-filter select {
            padding: 10px;
            border: 1px solid #ddd6fe;
            border-radius: 8px;
        }

        .applications-table-wrapper {
            overflow-x: auto;
        }

        .applications-table {
            width: 100%;
            border-collapse: collapse;
        }

        .applications-table th,
        .applications-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd6fe;
        }

        .applications-table th {
            background: #ede9fe;
            color: #30236b;
        }

        .applications-table tr:hover {
            background: #faf8ff;
        }

        .status-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
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

        .applications-pagination {
            margin-top: 25px;
        }

        .empty-message {
            padding: 20px;
            background: #f5f3ff;
            border-radius: 8px;
            text-align: center;
        }


/* Administrator application review buttons */
.review-actions {
    display: flex;
    gap: 8px;
    align-items: center;
    flex-wrap: wrap;
}

.review-actions form {
    margin: 0;
}

.approve-btn,
.reject-btn {
    padding: 9px 14px;
    border: none;
    border-radius: 7px;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    color: white;
}


/* Approve button - Green */
.review-actions button.approve-btn {
    background: #15803d !important;
    color: #ffffff !important;
    border: none;
}

.review-actions button.approve-btn:hover {
    background: #166534 !important;
}

/* Reject button - Red */
.review-actions button.reject-btn {
    background: #dc2626 !important;
    color: #ffffff !important;
    border: none;
}

.review-actions button.reject-btn:hover {
    background: #b91c1c !important;
}


.review-completed {
    color: #6b7280;
    font-size: 13px;
    font-weight: 600;
}

.review-alert-success {
    background: #d1fae5;
    color: #065f46;
    padding: 15px;
    border-radius: 8px;
    margin: 20px 0;
}

.review-alert-error {
    background: #fee2e2;
    color: #991b1b;
    padding: 15px;
    border-radius: 8px;
    margin: 20px 0;
}

    </style>
</head>

<body>

<header class="hero">
    <h1>Membership Applications</h1>
    <p>Review submitted membership applications</p>
</header>

<main class="container">

    <div class="card applications-card">

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


        <p>
            View membership applications submitted
            by registered users.
        </p>

        <div class="applications-toolbar">

            <a href="{{ route('dashboard.index') }}"
               class="btn">
                Back to Dashboard
            </a>

            <form method="GET"
                  action="{{ route('admin.applications.index') }}"
                  class="applications-filter">

                <label for="status">Filter:</label>

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

                <button type="submit" class="btn">
                    Apply Filter
                </button>

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
                            <th>Status</th>
                            <th>Date Submitted</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>

                        @foreach($applications as $application)

                            <tr>
                                <td>
                                    {{ $application->reference_no }}
                                </td>

                                <td>
                                    {{ $application->full_name }}
                                </td>

                                <td>
                                    {{ $application->email }}
                                </td>

                                <td>
                                    {{ $application->category?->name ?? 'Unavailable' }}
                                </td>

                                <td>
                                    <span class="status-badge
                                        status-{{ $application->status }}">
                                        {{ ucfirst($application->status) }}
                                    </span>
                                </td>

                                <td>
                                    {{ $application->created_at?->format('M d, Y') ?? 'N/A' }}
                                </td>


<td>
    @if($application->status === 'pending')

        <div class="review-actions">

            <!-- APPROVE APPLICATION -->
            <form
                method="POST"
                action="{{ route('admin.applications.update-status', $application) }}"
                onsubmit="return confirm('Are you sure you want to approve this application?');"
            >
                @csrf
                @method('PATCH')

                <input type="hidden"
                       name="status"
                       value="approved">

                <button type="submit" class="approve-btn">
                    Approve
                </button>
            </form>

            <!-- REJECT APPLICATION -->
            <form
                method="POST"
                action="{{ route('admin.applications.update-status', $application) }}"
                onsubmit="return confirm('Are you sure you want to reject this application?');"
            >
                @csrf
                @method('PATCH')

                <input type="hidden"
                       name="status"
                       value="rejected">

                <button type="submit" class="reject-btn">
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

</main>

</body>
</html>
