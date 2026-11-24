
@extends('layouts.admin')

@section('title', 'Edit Membership Level - MemberHub')
@section('page-title', 'Edit Membership Level')

@push('styles')
<style>
    .level-edit-page {
        width: 100%;
        max-width: 1100px;
        margin: 0 auto;
        padding: 15px 0 40px;
    }

    .level-edit-heading {
        margin-bottom: 25px;
    }

    .level-edit-heading h1 {
        margin: 0 0 10px;
        color: #30236b;
        font-size: 27px;
        font-weight: 700;
    }

    .level-edit-heading p {
        margin: 0;
        color: #756b9e;
        font-size: 14px;
    }

    .level-edit-card {
        width: 100%;
        background: #ffffff;
        border: 1px solid #ddd6fe;
        border-radius: 14px;
        padding: 30px;
        box-shadow: 0 10px 30px rgba(48, 35, 107, 0.06);
    }

    .level-edit-card h2 {
        margin: 0 0 24px;
        color: #30236b;
        font-size: 21px;
    }

    .level-edit-form {
        width: 100%;
    }

    .level-edit-field {
        width: 100%;
        margin-bottom: 22px;
    }

    .level-edit-field label {
        display: block;
        margin-bottom: 9px;
        color: #30236b;
        font-size: 14px;
        font-weight: 600;
    }

    .level-edit-field input,
    .level-edit-field textarea {
        display: block;
        width: 100%;
        max-width: none;
        padding: 13px 15px;
        border: 1px solid #ddd6fe;
        border-radius: 8px;
        background: #ffffff;
        color: #28245e;
        font-family: inherit;
        font-size: 14px;
        line-height: 1.5;
        box-sizing: border-box;
        transition: border-color 0.2s ease,
                    box-shadow 0.2s ease;
    }

    .level-edit-field input:focus,
    .level-edit-field textarea:focus {
        outline: none;
        border-color: #8768ef;
        box-shadow: 0 0 0 3px rgba(135, 104, 239, 0.12);
    }

    .level-edit-field textarea {
        min-height: 125px;
        resize: vertical;
    }

    .level-edit-field input::placeholder,
    .level-edit-field textarea::placeholder {
        color: #9b92b8;
    }

    .level-edit-help {
        margin-top: 7px;
        color: #756b9e;
        font-size: 12px;
    }

    .level-edit-error {
        margin-top: 7px;
        color: #991b1b;
        font-size: 12px;
    }

    .level-edit-alert {
        padding: 15px 18px;
        margin-bottom: 24px;
        background: #fee2e2;
        border-radius: 9px;
        color: #991b1b;
        font-size: 14px;
    }

    .level-edit-alert ul {
        margin: 10px 0 0;
        padding-left: 20px;
    }

    .level-edit-actions {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
        margin-top: 28px;
    }

    .level-edit-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 12px 20px;
        border: none;
        border-radius: 8px;
        font-family: inherit;
        font-size: 13px;
        font-weight: 700;
        text-decoration: none;
        cursor: pointer;
        transition: background 0.2s ease;
    }

    .level-edit-btn-primary {
        background: #8768ef;
        color: #ffffff;
    }

    .level-edit-btn-primary:hover {
        background: #7052d7;
        color: #ffffff;
    }

    .level-edit-btn-secondary {
        background: #495879;
        color: #ffffff;
    }

    .level-edit-btn-secondary:hover {
        background: #364462;
        color: #ffffff;
    }

    @media (max-width: 768px) {
        .level-edit-page {
            padding: 10px 0 30px;
        }

        .level-edit-heading h1 {
            font-size: 23px;
        }

        .level-edit-card {
            padding: 20px;
        }
    }
</style>
@endpush

@section('content')

<div class="level-edit-page">

    <div class="level-edit-heading">
        <h1>Edit Membership Level</h1>
        <p>
            Update membership level information,
            fees, validity periods, and benefits.
        </p>
    </div>

    <div class="level-edit-card">

        <h2>Edit Level: {{ $level->name }}</h2>

        @if($errors->any())
            <div class="level-edit-alert" role="alert">
                <strong>Please correct the following errors:</strong>

                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form
            action="{{ route('levels.update', $level) }}"
            method="POST"
            class="level-edit-form"
        >
            @csrf
            @method('PUT')

            <!-- MEMBERSHIP LEVEL NAME -->
            <div class="level-edit-field">

                <label for="name">
                    Membership Level Name
                </label>

                <input
                    type="text"
                    id="name"
                    name="name"
                    maxlength="100"
                    value="{{ old('name', $level->name) }}"
                    required
                >

                <div class="level-edit-help">
                    Enter the membership level name.
                </div>

                @error('name')
                    <div class="level-edit-error">
                        {{ $message }}
                    </div>
                @enderror

            </div>

            <!-- MEMBERSHIP FEE -->
            <div class="level-edit-field">

                <label for="fee">
                    Membership Fee (₱)
                </label>

                <input
                    type="number"
                    id="fee"
                    name="fee"
                    min="0"
                    max="99999999.99"
                    step="0.01"
                    value="{{ old('fee', $level->fee) }}"
                    required
                >

                @error('fee')
                    <div class="level-edit-error">
                        {{ $message }}
                    </div>
                @enderror

            </div>

            <!-- VALIDITY PERIOD -->
            <div class="level-edit-field">

                <label for="validity_months">
                    Validity Period (Months)
                </label>

                <input
                    type="number"
                    id="validity_months"
                    name="validity_months"
                    min="1"
                    max="120"
                    step="1"
                    value="{{ old('validity_months', $level->validity_months) }}"
                    required
                >

                @error('validity_months')
                    <div class="level-edit-error">
                        {{ $message }}
                    </div>
                @enderror

            </div>

            <!-- MEMBERSHIP BENEFITS -->
            <div class="level-edit-field">

                <label for="benefits">
                    Membership Benefits
                </label>

                <textarea
                    id="benefits"
                    name="benefits"
                    rows="5"
                    maxlength="5000"
                    placeholder="Describe membership benefits"
                >{{ old('benefits', $level->benefits) }}</textarea>

                @error('benefits')
                    <div class="level-edit-error">
                        {{ $message }}
                    </div>
                @enderror

            </div>

            <!-- FORM ACTIONS -->
            <div class="level-edit-actions">

                <button
                    type="submit"
                    class="level-edit-btn level-edit-btn-primary"
                >
                    Update Level
                </button>

                <a
                    href="{{ route('levels.index') }}"
                    class="level-edit-btn level-edit-btn-secondary"
                >
                    Cancel
                </a>

            </div>

        </form>

    </div>

</div>

@endsection
