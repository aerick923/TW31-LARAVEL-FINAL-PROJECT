
@extends('layouts.admin')

@section('title', 'Register New Member')

@section('page-title', 'Register New Member')

@push('styles')
<style>
    .member-create-page {
        max-width: 700px;
        margin: 0 auto;
    }

    .member-create-page .card {
        width: 100%;
        max-width: none;
        margin: 0;
        padding: 30px;
    }

    .member-create-page h2 {
        color: #30236b;
        margin-bottom: 10px;
    }

    .member-create-description {
        color: #665a91;
        margin-bottom: 25px;
    }

    .member-create-page .form-group {
        margin-bottom: 20px;
    }

    .member-create-page label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: #30236b;
    }

    .member-create-page input,
    .member-create-page select {
        width: 100%;
        padding: 12px;
        border: 1px solid #dcd3ff;
        border-radius: 8px;
        background: white;
        box-sizing: border-box;
    }

    .member-create-page input:focus,
    .member-create-page select:focus {
        outline: 2px solid #8b70ef;
        outline-offset: 1px;
    }

    .member-create-page .member-form-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-top: 25px;
    }

    @media (max-width: 768px) {
        .member-create-page .card {
            padding: 20px;
        }
    }
</style>
@endpush

@section('content')

<div class="member-create-page">

    <div class="card">

        <h2>Member Information</h2>

        <p class="member-create-description">
            Enter the membership information below.
        </p>

        @if ($errors->any())
            <div class="error-box" role="alert">
                Please correct the errors below.
            </div>
        @endif

        <form action="{{ route('members.store') }}"
              method="POST">

            @csrf

            <!-- FULL NAME -->

            <div class="form-group">

                <label for="name">Full Name</label>

                <input
                    type="text"
                    id="name"
                    name="name"
                    value="{{ old('name') }}"
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
                    value="{{ old('email') }}"
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
                    value="{{ old('phone') }}"
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

                <select id="category"
                        name="category"
                        required>

                    <option value="">
                        Select Category
                    </option>

                    @foreach(['Student', 'Regular', 'Senior Citizen', 'Corporate'] as $category)

                        <option
                            value="{{ $category }}"
                            @selected(old('category') == $category)
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

                <select id="level"
                        name="level"
                        required>

                    <option value="">
                        Select Level
                    </option>

                    @foreach($levels as $level)

                        <option
                            value="{{ $level->name }}"
                            @selected(old('level') == $level->name)
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
                    Register Member
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
