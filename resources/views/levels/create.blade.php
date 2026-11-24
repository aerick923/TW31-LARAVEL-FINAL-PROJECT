
@extends('layouts.admin')

@section('title', 'Add Membership Level - MemberHub')
@section('page-title', 'Add Membership Level')

@push('styles')
<style>
    .level-create-page {
        width: 100%;
        max-width: 1100px;
        margin: 0 auto;
        padding: 15px 0 40px;
    }

    .level-create-heading {
        margin-bottom: 25px;
    }

    .level-create-heading h1 {
        margin: 0 0 10px;
        color: #30236b;
        font-size: 27px;
        font-weight: 700;
    }

    .level-create-heading p {
        margin: 0;
        color: #756b9e;
        font-size: 14px;
    }

    .level-create-card {
        width: 100%;
        background: #ffffff;
        border: 1px solid #ddd6fe;
        border-radius: 14px;
        padding: 30px;
        box-shadow: 0 10px 30px rgba(48, 35, 107, 0.06);
    }

    .level-create-card h2 {
        margin: 0 0 24px;
        color: #30236b;
        font-size: 21px;
    }

    .level-create-form {
        width: 100%;
    }

    .level-create-field {
        width: 100%;
        margin-bottom: 22px;
    }

    .level-create-field label {
        display: block;
        margin-bottom: 9px;
        color: #30236b;
        font-size: 14px;
        font-weight: 600;
    }

    .level-create-field input,
    .level-create-field textarea {
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

    .level-create-field input:focus,
    .level-create-field textarea:focus {
        outline: none;
        border-color: #8768ef;
        box-shadow: 0 0 0 3px rgba(135, 104, 239, 0.12);
    }

    .level-create-field textarea {
        min-height: 125px;
        resize: vertical;
    }

    .level-create-field input::placeholder,
    .level-create-field textarea::placeholder {
        color: #9b92b8;
    }

    .level-create-help {
        margin-top: 7px;
        color: #756b9e;
        font-size: 12px;
    }

    .level-create-error {
        margin-top: 7px;
        color: #991b1b;
        font-size: 12px;
    }

    .level-create-alert {
        padding: 15px 18px;
        margin-bottom: 24px;
        background: #fee2e2;
        border-radius: 9px;
        color: #991b1b;
        font-size: 14px;
    }

    .level-create-alert ul {
        margin: 10px 0 0;
        padding-left: 20px;
    }

    .level-create-actions {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
        margin-top: 28px;
    }

    .level-create-btn {
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

    .level-create-btn-primary {
        background: #8768ef;
        color: #ffffff;
    }

    .level-create-btn-primary:hover {
        background: #7052d7;
        color: #ffffff;
    }

    .level-create-btn-secondary {
        background: #495879;
        color: #ffffff;
    }

    .level-create-btn-secondary:hover {
        background: #364462;
        color: #ffffff;
    }

    @media (max-width: 768px) {
        .level-create-page {
            padding: 10px 0 30px;
        }

        .level-create-heading h1 {
            font-size: 23px;
        }

        .level-create-card {
            padding: 20px;
        }
    }
</style>
@endpush

@section('content')

<div class="level-create-page">

    <div class="level-create-heading">
        <h1>Add Membership Level</h1>
        <p>Create a new membership level.</p>
    </div>

    <div class="level-create-card">

        <h2>Level Information</h2>

        @if($errors->any())
            <div class="level-create-alert" role="alert">
                <strong>Please correct the following errors:</strong>

                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form
            action="{{ route('levels.store') }}"
            method="POST"
            class="level-create-form"
        >
            @csrf

            <!-- MEMBERSHIP LEVEL NAME -->
            <div class="level-create-field">

                <label for="name">
                    Membership Level Name
                </label>

                <input
                    type="text"
                    id="name"
                    name="name"
                    maxlength="100"
                    placeholder="Enter membership level name"
                    value="{{ old('name') }}"
                    required
                >

                <div class="level-create-help">
                    Enter a unique membership level name.
                </div>

                @error('name')
                    <div class="level-create-error">
                        {{ $message }}
                    </div>
                @enderror

            </div>

            <!-- MEMBERSHIP FEE -->
            <div class="level-create-field">

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
                    value="{{ old('fee', '0.00') }}"
                    required
                >

                @error('fee')
                    <div class="level-create-error">
                        {{ $message }}
                    </div>
                @enderror

            </div>

            <!-- VALIDITY PERIOD -->
            <div class="level-create-field">

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
                    value="{{ old('validity_months', 12) }}"
                    required
                >

                @error('validity_months')
                    <div class="level-create-error">
                        {{ $message }}
                    </div>
                @enderror

            </div>

            <!-- MEMBERSHIP BENEFITS -->
            <div class="level-create-field">

                <label for="benefits">
                    Membership Benefits
                </label>

                <textarea
                    id="benefits"
                    name="benefits"
                    rows="5"
                    maxlength="5000"
                    placeholder="Describe membership benefits"
                >{{ old('benefits') }}</textarea>

                @error('benefits')
                    <div class="level-create-error">
                        {{ $message }}
                    </div>
                @enderror

            </div>

            <!-- FORM ACTIONS -->
            <div class="level-create-actions">

                <button
                    type="submit"
                    class="level-create-btn level-create-btn-primary"
                >
                    Save Level
                </button>

                <a
                    href="{{ route('levels.index') }}"
                    class="level-create-btn level-create-btn-secondary"
                >
                    Cancel
                </a>

            </div>

        </form>

    </div>

</div>

@endsection
