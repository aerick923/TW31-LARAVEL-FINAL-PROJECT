
@extends('layouts.admin')

@section('title', 'Membership Categories - MemberHub')
@section('page-title', 'Membership Categories')

@push('styles')
<style>
    .category-page {
        width: 100%;
        max-width: 1100px;
        margin: 0 auto;
        padding: 15px 0 40px;
    }

    .category-heading {
        margin-bottom: 25px;
    }

    .category-heading h1 {
        margin: 0 0 10px;
        color: #30236b;
        font-size: 27px;
        font-weight: 700;
    }

    .category-heading p {
        margin: 0;
        color: #756b9e;
        font-size: 14px;
    }

    .category-card {
        width: 100%;
        background: #ffffff;
        border: 1px solid #ddd6fe;
        border-radius: 14px;
        padding: 25px;
        box-shadow: 0 10px 30px rgba(48, 35, 107, 0.06);
    }

    .category-card h2 {
        margin: 0 0 22px;
        color: #30236b;
        font-size: 21px;
        font-weight: 700;
    }

    .category-toolbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        flex-wrap: wrap;
        margin: 20px 0;
    }

    .category-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 11px 16px;
        border: none;
        border-radius: 8px;
        font-family: inherit;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
        cursor: pointer;
        white-space: nowrap;
        transition: background 0.2s ease;
    }

    .category-btn-purple {
        background: #8768ef;
        color: #ffffff;
    }

    .category-btn-purple:hover {
        background: #7052d7;
        color: #ffffff;
    }

    .category-btn-gray {
        background: #495879;
        color: #ffffff;
    }

    .category-btn-gray:hover {
        background: #364462;
        color: #ffffff;
    }

    .category-btn-green {
        background: #348d6d;
        color: #ffffff;
    }

    .category-btn-green:hover {
        background: #287557;
        color: #ffffff;
    }

    .category-btn-danger {
        background: #dc2626;
        color: #ffffff;
    }

    .category-btn-danger:hover {
        background: #b91c1c;
        color: #ffffff;
    }

    .category-table-wrapper {
        width: 100%;
        overflow-x: auto;
        border: 1px solid #a997f5;
        border-radius: 10px;
    }

    .category-table {
        width: 100%;
        border-collapse: collapse;
        background: #ffffff;
        font-size: 13px;
    }

    .category-table thead {
        background: #ede9fe;
    }

    .category-table th {
        padding: 16px 14px;
        text-align: left;
        color: #30236b;
        font-weight: 700;
        white-space: nowrap;
    }

    .category-table td {
        padding: 15px 14px;
        border-top: 1px solid #e7e0fa;
        color: #30236b;
        vertical-align: middle;
    }

    .category-table tbody tr:hover {
        background: #faf9ff;
    }

    .category-actions {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
    }

    .category-actions form {
        display: inline-flex;
        margin: 0;
    }

    .category-status {
        display: inline-block;
        padding: 7px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }

    .category-status-active {
        background: #d1fae5;
        color: #065f46;
    }

    .category-status-inactive {
        background: #fee2e2;
        color: #991b1b;
    }

    .category-alert-success {
        padding: 15px 18px;
        margin-bottom: 20px;
        background: #d1fae5;
        color: #065f46;
        border-radius: 8px;
        font-size: 14px;
    }

    .category-alert-error {
        padding: 15px 18px;
        margin-bottom: 20px;
        background: #fee2e2;
        color: #991b1b;
        border-radius: 8px;
        font-size: 14px;
    }

    .category-empty {
        text-align: center;
        color: #756b9e;
        padding: 25px !important;
    }

    @media (max-width: 768px) {
        .category-page {
            padding: 10px 0 30px;
        }

        .category-heading h1 {
            font-size: 23px;
        }

        .category-card {
            padding: 18px;
        }

        .category-table {
            min-width: 750px;
        }
    }
</style>
@endpush

@section('content')

<div class="category-page">

    <!-- PAGE HEADING -->
    <div class="category-heading">
        <h1>Membership Categories</h1>

        <p>
            Manage membership categories and their information.
        </p>
    </div>

    <!-- CATEGORY MANAGEMENT CARD -->
    <div class="category-card">

        <h2>Membership Categories</h2>

        <!-- SUCCESS MESSAGE -->
        @if(session('success'))
            <div class="category-alert-success" role="status">
                {{ session('success') }}
            </div>
        @endif

        <!-- ERROR MESSAGE -->
        @if(session('error'))
            <div class="category-alert-error" role="alert">
                {{ session('error') }}
            </div>
        @endif

        <!-- VALIDATION ERRORS -->
        @if($errors->any())
            <div class="category-alert-error" role="alert">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- ACTION BUTTONS -->
        <div class="category-toolbar">

            <a
                href="{{ route('members.index') }}"
                class="category-btn category-btn-gray"
            >
                Back to Members
            </a>

            <a
                href="{{ route('categories.create') }}"
                class="category-btn category-btn-green"
            >
                + Add Category
            </a>

        </div>

        <!-- CATEGORY TABLE -->
        <div class="category-table-wrapper">

            <table class="category-table">

                <thead>
                    <tr>
                        <th>Category</th>
                        <th>Level</th>
                        <th>Fee</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($categories as $category)

                        <tr>

                            <!-- CATEGORY NAME -->
                            <td>
                                {{ $category->name }}
                            </td>

                            <!-- CATEGORY LEVEL -->
                            <td>
                                {{ $category->level ?? 'Not specified' }}
                            </td>

                            <!-- CATEGORY FEE -->
                            <td>
                                ₱{{ number_format($category->fee ?? 0, 2) }}
                            </td>

                            <!-- CATEGORY STATUS -->
                            <td>

                                @if($category->is_active)

                                    <span class="category-status category-status-active">
                                        Active
                                    </span>

                                @else

                                    <span class="category-status category-status-inactive">
                                        Inactive
                                    </span>

                                @endif

                            </td>

                            <!-- CATEGORY ACTIONS -->
                            <td>

                                <div class="category-actions">

                                    <a
                                        href="{{ route('categories.edit', $category) }}"
                                        class="category-btn category-btn-purple"
                                    >
                                        Edit
                                    </a>

                                    @if($category->is_active)

                                        <form
                                            action="{{ route('categories.destroy', $category) }}"
                                            method="POST"
                                            onsubmit="return confirm('Are you sure you want to deactivate this category?');"
                                        >
                                            @csrf
                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="category-btn category-btn-danger"
                                            >
                                                Deactivate
                                            </button>

                                        </form>

                                    @endif

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="5" class="category-empty">
                                No membership categories found.
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection
