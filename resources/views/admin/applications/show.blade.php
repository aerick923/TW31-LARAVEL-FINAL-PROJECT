
@extends('layouts.admin')

@section('title', 'Review Membership Application')
@section('page-title', 'Review Application')

@push('styles')
<style>
    .application-review {
        max-width: 1050px;
        margin: 0 auto;
    }

    .application-review h1 {
        color: #30236b;
        font-size: 28px;
        margin: 0 0 8px;
    }

    .application-review .subtitle {
        color: #74649d;
        margin-bottom: 25px;
    }

    .application-review .review-card {
        background: white;
        border: 1px solid #ded6ff;
        border-radius: 14px;
        padding: 28px;
        margin-bottom: 20px;
        box-shadow: 0 8px 25px rgba(48, 35, 107, 0.05);
    }

    .application-review h2 {
        color: #30236b;
        font-size: 20px;
        margin: 0 0 22px;
    }

    .application-review .details-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 15px;
    }

    .application-review .detail-item {
        background: #faf9ff;
        border: 1px solid #e7e0ff;
        border-radius: 10px;
        padding: 16px;
        min-width: 0;
        overflow-wrap: anywhere;
    }

    .application-review .detail-label {
        color: #74649d;
        font-size: 12px;
        margin-bottom: 8px;
    }

    .application-review .detail-value {
        color: #30236b;
        font-weight: 600;
        font-size: 14px;
    }

    .application-review .status {
        display: inline-block;
        border-radius: 20px;
        padding: 7px 13px;
        font-size: 12px;
        font-weight: 600;
    }

    .application-review .status-pending {
        background: #fff0c2;
        color: #946000;
    }

    .application-review .status-approved {
        background: #d9fae8;
        color: #047857;
    }

    .application-review .status-rejected {
        background: #fee2e2;
        color: #b91c1c;
    }

    .application-review .review-actions {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 12px;
        margin-top: 20px;
    }

    .application-review .review-actions form {
        margin: 0;
    }

    .application-review .review-btn {
        display: inline-block;
        border: 0;
        border-radius: 8px;
        padding: 12px 18px;
        background: #8064ed;
        color: white;
        text-decoration: none;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.2s ease;
    }

    .application-review .review-btn:hover {
        background: #6d52d6;
    }

    .application-review .btn-green {
        background: #2f8b6e;
    }

    .application-review .btn-green:hover {
        background: #267257;
    }

    .application-review .btn-red {
        background: #b83b51;
    }

    .application-review .btn-red:hover {
        background: #982c41;
    }

    .application-review .btn-gray {
        background: #4d5b80;
    }

    .application-review .btn-gray:hover {
        background: #3c4969;
    }

    /* Disabled approval button */

    .application-review .review-btn:disabled,
    .application-review .review-btn:disabled:hover {
        background: #a7b0aa;
        color: #ffffff;
        cursor: not-allowed;
        opacity: 0.7;
    }

    .application-review .warning {
        background: #fff8e5;
        border: 1px solid #f5d58a;
        border-radius: 9px;
        padding: 15px;
        color: #8a5700;
        margin-bottom: 18px;
        line-height: 1.6;
    }

    .application-review .review-message {
        color: #665a91;
        line-height: 1.6;
    }

    @media (max-width: 650px) {
        .application-review .details-grid {
            grid-template-columns: 1fr;
        }

        .application-review .review-card {
            padding: 18px;
        }

        .application-review h1 {
            font-size: 24px;
        }
    }
</style>
@endpush

@section('content')

<div class="application-review">

    <h1>Review Membership Application</h1>

    <p class="subtitle">
        Review the submitted information before making a decision.
    </p>

    <!-- VALIDATION ERRORS -->

    @if($errors->any())
        <div class="warning" role="alert">
            @foreach($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <!-- APPLICATION INFORMATION -->

    <div class="review-card">

        <h2>Application Information</h2>

        <div class="details-grid">

            <div class="detail-item">
                <div class="detail-label">
                    Application Reference
                </div>

                <div class="detail-value">
                    {{ $application->reference_no }}
                </div>
            </div>

            <div class="detail-item">
                <div class="detail-label">
                    Application Status
                </div>

                <div class="detail-value">
                    <span class="status status-{{ $application->status }}">
                        {{ ucfirst($application->status) }}
                    </span>
                </div>
            </div>

            <div class="detail-item">
                <div class="detail-label">
                    Full Name
                </div>

                <div class="detail-value">
                    {{ $application->full_name }}
                </div>
            </div>

            <div class="detail-item">
                <div class="detail-label">
                    Email Address
                </div>

                <div class="detail-value">
                    {{ $application->email }}
                </div>
            </div>

            <div class="detail-item">
                <div class="detail-label">
                    Phone Number
                </div>

                <div class="detail-value">
                    {{ $application->phone ?: 'Not Available' }}
                </div>
            </div>

            <div class="detail-item">
                <div class="detail-label">
                    Address
                </div>

                <div class="detail-value">
                    {{ $application->address ?: 'Not Available' }}
                </div>
            </div>

            <div class="detail-item">
                <div class="detail-label">
                    Membership Category
                </div>

                <div class="detail-value">
                    {{ $application->category?->name ?? 'Not Available' }}
                </div>
            </div>

            <div class="detail-item">
                <div class="detail-label">
                    Membership Level
                </div>

                <div class="detail-value">
                    {{ $application->membershipLevel?->name ?? 'Not Selected' }}
                </div>
            </div>

            <div class="detail-item">
                <div class="detail-label">
                    Membership Fee
                </div>

                <div class="detail-value">
                    @if($application->membershipLevel)
                        ₱{{ number_format(
                            $application->membershipLevel->fee,
                            2
                        ) }}
                    @else
                        Unavailable
                    @endif
                </div>
            </div>

            <div class="detail-item">
                <div class="detail-label">
                    Date Submitted
                </div>

                <div class="detail-value">
                    {{ $application->created_at?->format('F d, Y h:i A') }}
                </div>
            </div>

        </div>

    </div>

    <!-- APPLICATION DECISION -->

    <div class="review-card">

        <h2>Application Decision</h2>

        @if($application->status === 'pending')

            <!-- MISSING CATEGORY OR LEVEL WARNING -->

            @if(
                !$application->category ||
                !$application->membershipLevel
            )
                <div class="warning" role="alert">
                    This application cannot be approved because
                    its membership category or membership level
                    is missing.

                    The administrator may reject the application,
                    but approval will remain unavailable until
                    the missing information is resolved.
                </div>
            @endif

            <!-- APPROVE AND REJECT BUTTONS -->

            <div class="review-actions">

                <!-- APPROVE APPLICATION -->

                <form
                    action="{{ route(
                        'admin.applications.update-status',
                        $application
                    ) }}"
                    method="POST"
                    onsubmit="return confirm('Approve this membership application?');">

                    @csrf
                    @method('PATCH')

                    <input
                        type="hidden"
                        name="status"
                        value="approved"
                    >

                    <button
                        type="submit"
                        class="review-btn btn-green"
                        @disabled(
                            !$application->category ||
                            !$application->membershipLevel
                        )
                    >
                        Approve Application
                    </button>

                </form>

                <!-- REJECT APPLICATION -->

                <form
                    action="{{ route(
                        'admin.applications.update-status',
                        $application
                    ) }}"
                    method="POST"
                    onsubmit="return confirm('Reject this membership application?');">

                    @csrf
                    @method('PATCH')

                    <input
                        type="hidden"
                        name="status"
                        value="rejected"
                    >

                    <button
                        type="submit"
                        class="review-btn btn-red"
                    >
                        Reject Application
                    </button>

                </form>

            </div>

        @else

            <!-- ALREADY REVIEWED APPLICATION -->

            <p class="review-message">
                This application has already been reviewed.
                Its current status is
                <strong>
                    {{ ucfirst($application->status) }}
                </strong>.
            </p>

            <!-- VIEW LINKED MEMBER PROFILE -->

            @if(
                $application->status === 'approved' &&
                $application->member
            )

                <div class="review-actions">

                    <a
                        href="{{ route(
                            'members.show',
                            $application->member
                        ) }}"
                        class="review-btn"
                    >
                        View Member Profile
                    </a>

                </div>

            @elseif($application->status === 'approved')

                <div class="warning" role="alert">
                    This application is approved, but its linked
                    member record is unavailable.
                </div>

            @endif

        @endif

    </div>

    <!-- BACK TO APPLICATIONS -->

    <div class="review-actions">

        <a
            href="{{ route('admin.applications.index') }}"
            class="review-btn btn-gray"
        >
            Back to Applications
        </a>

    </div>

</div>

@endsection
