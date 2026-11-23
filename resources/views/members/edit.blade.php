
@extends('layouts.admin')

@section('title', 'Edit Member')

@section('page-title', 'Edit Member')

@push('styles')
<style>
    .member-edit-page {
        max-width: 700px;
        width: 100%;
        margin: 0 auto;
    }

    .member-edit-page .card {
        width: 100%;
        max-width: none;
        margin: 0;
        padding: 30px;
    }

    .member-edit-page h2 {
        color: #30236b;
        margin-bottom: 10px;
    }

    .member-edit-description {
        color: #665a91;
        margin-bottom: 25px;
    }

    .member-edit-page .form-group {
        margin-bottom: 20px;
    }

    .member-edit-page label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: #30236b;
    }

    .member-edit-page input,
    .member-edit-page select {
        width: 100%;
        padding: 12px;
        border: 1px solid #dcd3ff;
        border-radius: 8px;
        background: white;
        box-sizing: border-box;
    }

    .member-edit-page input:focus,
    .member-edit-page select:focus {
        outline: 2px solid #8b70ef;
        outline-offset: 1px;
    }

    .member-edit-page input[readonly] {
        background: #f3f1fa;
        color: #665a91;
        cursor: not-allowed;
    }

    .member-edit-page .member-form-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-top: 25px;
    }

    .member-edit-page .error {
        color: #9b1c1c;
        font-size: 13px;
        margin-top: 6px;
    }

    @media (max-width: 768px) {
        .member-edit-page .card {
            padding: 20px;
        }
    }
</style>
@endpush

@section('content')

<div class="member-edit-page">

    <div class="card">

        <h2>Member Information</h2>

        <p class="member-edit-description">
            Update the existing membership information below.
        </p>

        @if ($errors->any())
            <div class="error-box" role="alert">
                Please correct the errors below.
            </div>
        @endif

        <form action="{{ route('members.update', $member) }}"
              method="POST">

            @csrf
            @method('PUT')

            <!-- MEMBERSHIP NUMBER -->

            <div class="form-group">

                <label for="membership_number">
                    Membership Number
                </label>

                <input
                    type="text"
                    id="membership_number"
                    value="{{ $member->membership_number }}"
                    readonly
                >

            </div>

            <!-- FULL NAME -->

            <div class="form-group">

                <label for="name">Full Name</label>

                <input
                    type="text"
                    id="name"
                    name="name"
                    value="{{ old('name', $member->name) }}"
                    required
                >

                @error('name')
                    <div class="error">{{ $message }}</div>
                @enderror

            </div>

            <!-- EMAIL ADDRESS -->

            <div class="form-group">

                <label for="email">Email Address</label>

                <input
                    type="email"
                    id="email"
                    name="email"
                    value="{{ old('email', $member->email) }}"
                    required
                >

                @error('email')
                    <div class="error">{{ $message }}</div>
                @enderror

            </div>

            <!-- PHONE NUMBER -->

            <div class="form-group">

                <label for="phone">Phone Number</label>

                <input
                    type="text"
                    id="phone"
                    name="phone"
                    value="{{ old('phone', $member->phone) }}"
                    inputmode="numeric"
                    maxlength="11"
                    pattern="09[0-9]{9}"
                    placeholder="09XXXXXXXXX"
                    title="Enter exactly 11 digits starting with 09."
                >

                @error('phone')
                    <div class="error">{{ $message }}</div>
                @enderror

            </div>

            <!-- MEMBERSHIP CATEGORY -->

            <div class="form-group">

                <label for="category">
                    Membership Category
                </label>

                <select
                    id="category"
                    name="category"
                    required
                >

                    @foreach(['Student', 'Regular', 'Senior Citizen', 'Corporate'] as $category)

                        <option
                            value="{{ $category }}"
                            @selected(
                                old('category', $member->category) == $category
                            )
                        >
                            {{ $category }}
                        </option>

                    @endforeach

                </select>

                @error('category')
                    <div class="error">{{ $message }}</div>
                @enderror

            </div>

            <!-- MEMBERSHIP LEVEL -->

            <div class="form-group">

                <label for="level">
                    Membership Level
                </label>

                <select
                    id="level"
                    name="level"
                    required
                >

                    @foreach($levels as $level)

                        <option
                            value="{{ $level->name }}"
                            @selected(
                                old('level', $member->level) == $level->name
                            )
                        >
                            {{ $level->name }} —
                            ₱{{ number_format($level->fee, 2) }}
                        </option>

                    @endforeach

                </select>

                @error('level')
                    <div class="error">{{ $message }}</div>
                @enderror

            </div>

            <!-- FORM ACTIONS -->

            <div class="member-form-actions">

                <button type="submit" class="btn">
                    Save Changes
                </button>

                <a href="{{ route('members.index') }}"
                   class="btn btn-gray">
                    Cancel
                </a>

            </div>

        </form>

    </div>

</div>

@endsection
