
@extends('layouts.admin')

@section('title', 'Member Profile')

@section('page-title', 'Member Profile')

@push('styles')
<style>
    .member-profile-page {
        max-width: 750px;
        margin: 0 auto;
        width: 100%;
    }

    .member-profile-page .card {
        width: 100%;
        max-width: none;
        margin: 0;
        padding: 30px;
    }

    .member-profile-page h2 {
        color: #30236b;
        font-size: 24px;
        margin-bottom: 25px;
    }

    .member-profile-page .detail {
        display: flex;
        flex-direction: column;
        gap: 8px;
        padding: 15px 0;
        border-bottom: 1px solid #e5e0f5;
    }

    .member-profile-page .detail strong {
        color: #50429a;
        font-size: 14px;
    }

    .member-profile-page .detail span {
        color: #28245e;
        font-size: 15px;
        overflow-wrap: anywhere;
    }

    .member-profile-page .profile-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        margin-top: 25px;
    }

    @media (max-width: 768px) {
        .member-profile-page .card {
            padding: 20px;
        }
    }
</style>
@endpush

@section('content')

<div class="member-profile-page">

    <div class="card">

        <h2>{{ $member->name }}</h2>

        <!-- MEMBERSHIP NUMBER -->

        <div class="detail">
            <strong>Membership Number</strong>
            <span>{{ $member->membership_number }}</span>
        </div>

        <!-- PERSONAL INFORMATION -->

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

        <!-- MEMBERSHIP INFORMATION -->

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

        <!-- MEMBERSHIP STATUS -->

        <div class="detail">
            <strong>Membership Status</strong>
            <span>{{ $member->status }}</span>
        </div>

        <div class="detail">
            <strong>Date Registered</strong>
            <span>
                {{ $member->created_at->format('F d, Y') }}
            </span>
        </div>

        <!-- ACTION BUTTONS -->

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

</div>

@endsection
