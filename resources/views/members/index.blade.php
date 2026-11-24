
@extends('layouts.admin')

@section('title', 'Member Management')
@section('page-title', 'Member Management')

@push('styles')
<style>
    .members-page {
        width: 100%;
    }

    .members-page .card {
        width: 100%;
        max-width: none;
        margin: 0;
        padding: 28px;
    }

    .members-page h2 {
        margin: 0;
        color: #30236b;
    }

    /* Page Header */

    .members-page .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
        margin-bottom: 25px;
    }

    .members-page .page-subtitle {
        color: #74649d;
        font-size: 14px;
        margin: 8px 0 0;
    }

    /* Search and Filters */

    .members-page .filter-panel {
        background: #faf9ff;
        border: 1px solid #e1d9ff;
        border-radius: 12px;
        padding: 22px;
        margin-bottom: 25px;
    }

    .members-page .filter-group {
        margin-bottom: 18px;
    }

    .members-page .filter-group label {
        display: block;
        font-size: 14px;
        font-weight: 600;
        color: #30236b;
        margin-bottom: 8px;
    }

    .members-page .filter-group input,
    .members-page .filter-group select {
        display: block;
        width: 100%;
        min-width: 0;
        padding: 12px;
        border: 1px solid #dcd3ff;
        border-radius: 8px;
        background: #ffffff;
        color: #30236b;
        font-size: 14px;
    }

    .members-page .filter-group input:focus,
    .members-page .filter-group select:focus {
        outline: none;
        border-color: #8064ed;
        box-shadow: 0 0 0 3px rgba(128, 100, 237, 0.12);
    }

    .members-page .filter-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 15px;
    }

    .members-page .filter-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-top: 5px;
    }

    .members-page .filter-actions .btn {
        text-decoration: none;
    }

    /* Member Table */

    .members-page .table-wrapper {
        width: 100%;
        overflow-x: auto;
    }

    .members-page table {
        width: 100%;
        border-collapse: collapse;
    }

    .members-page th,
    .members-page td {
        vertical-align: middle;
    }

    .members-page .actions {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 6px;
    }

    .members-page .actions form {
        margin: 0;
    }

    .members-page .actions .btn {
        white-space: nowrap;
    }

    .members-page .member-count {
        color: #74649d;
        font-size: 13px;
        margin-bottom: 15px;
    }

    .members-page .pagination {
        margin-top: 22px;
    }

    /* Responsive Layout */

    @media (max-width: 900px) {
        .members-page .filter-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 600px) {
        .members-page .card {
            padding: 18px;
        }

        .members-page .filter-panel {
            padding: 16px;
        }

        .members-page .filter-grid {
            grid-template-columns: 1fr;
        }

        .members-page .page-header {
            align-items: stretch;
        }

        .members-page .page-header .btn {
            text-align: center;
        }
    }
</style>
@endpush

@section('content')

<div class="members-page">

    <div class="card">

        <!-- PAGE HEADER -->

        <div class="page-header">

            <div>
                <h2>Registered Members</h2>

                <p class="page-subtitle">
                    Search, filter, and manage registered members.
                </p>
            </div>

            <a href="{{ route('members.create') }}"
               class="btn btn-green">
                + Add New Member
            </a>

        </div>

        <!-- SUCCESS AND ERROR MESSAGES -->

        @if(session('success'))
            <div class="success" role="status">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="error-box" role="alert">
                {{ session('error') }}
            </div>
        @endif

        <!-- ADVANCED SEARCH AND FILTERS -->

        <div class="filter-panel">

            <form action="{{ route('members.index') }}"
                  method="GET">

                <!-- SEARCH -->

                <div class="filter-group">

                    <label for="member-search">
                        Search Members
                    </label>

                    <input
                        type="text"
                        id="member-search"
                        name="search"
                        placeholder="Name, email, membership number, category, or level"
                        value="{{ request('search') }}"
                        maxlength="255"
                    >

                </div>

                <!-- FILTER DROPDOWNS -->

                <div class="filter-grid">

                    <!-- STATUS -->

                    <div class="filter-group">

                        <label for="member-status">
                            Membership Status
                        </label>

                        <select id="member-status"
                                name="status">

                            <option value="">
                                All Statuses
                            </option>

                            <option value="Active"
                                @selected(request('status') === 'Active')>
                                Active
                            </option>

                            <option value="Inactive"
                                @selected(request('status') === 'Inactive')>
                                Inactive
                            </option>

                        </select>

                    </div>

                    <!-- CATEGORY -->

                    <div class="filter-group">

                        <label for="member-category">
                            Membership Category
                        </label>

                        <select id="member-category"
                                name="category">

                            <option value="">
                                All Categories
                            </option>

                            @foreach([
                                'Student',
                                'Regular',
                                'Senior Citizen',
                                'Corporate'
                            ] as $category)

                                <option value="{{ $category }}"
                                    @selected(request('category') === $category)>
                                    {{ $category }}
                                </option>

                            @endforeach

                        </select>

                    </div>

                    <!-- LEVEL -->

                    <div class="filter-group">

                        <label for="member-level">
                            Membership Level
                        </label>

                        <select id="member-level"
                                name="level">

                            <option value="">
                                All Levels
                            </option>

                            @foreach([
                                'Basic',
                                'Silver',
                                'Gold',
                                'Platinum'
                            ] as $level)

                                <option value="{{ $level }}"
                                    @selected(request('level') === $level)>
                                    {{ $level }}
                                </option>

                            @endforeach

                        </select>

                    </div>

                </div>

                <!-- SEARCH BUTTONS -->

                <div class="filter-actions">

                    <button type="submit" class="btn">
                        Search Members
                    </button>

                    <a href="{{ route('members.index') }}"
                       class="btn btn-gray">
                        Clear Filters
                    </a>

                </div>

            </form>

        </div>

        <!-- SEARCH RESULTS COUNT -->

        <p class="member-count">
            Showing {{ $members->firstItem() ?? 0 }}
            to {{ $members->lastItem() ?? 0 }}
            of {{ $members->total() }} members
        </p>

        <!-- MEMBER RECORDS -->

        <div class="table-wrapper">

            <table>

                <thead>
                    <tr>
                        <th>Membership No.</th>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Level</th>
                        <th>Membership Fee</th>
                        <th>Status</th>
                        <th>Membership Validity</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($members as $member)

                        <tr>

                            <td>
                                {{ $member->membership_number }}
                            </td>

                            <td>
                                {{ $member->name }}
                            </td>

                            <td>
                                {{ $member->category }}
                            </td>

                            <td>
                                {{ $member->level }}
                            </td>

                            <td>
                                @if($member->membershipLevel)
                                    ₱{{ number_format(
                                        $member->membershipLevel->fee,
                                        2
                                    ) }}
                                @else
                                    Fee unavailable
                                @endif
                            </td>

                            <td>
                                {{ $member->status }}
                            </td>

                            <td>
                                {{ $member->membership_validity }}
                            </td>

                            <td>

                                <div class="actions">

                                    <!-- VIEW -->

                                    <a href="{{ route('members.show', $member) }}"
                                       class="btn">
                                        View
                                    </a>

                                    <!-- EDIT -->

                                    <a href="{{ route('members.edit', $member) }}"
                                       class="btn btn-orange">
                                        Edit
                                    </a>

                                    <!-- ACTIVATE / DEACTIVATE -->

                                    <form
                                        action="{{ route('members.toggleStatus', $member) }}"
                                        method="POST">

                                        @csrf
                                        @method('PATCH')

                                        <button type="submit"
                                                class="btn btn-gray">

                                            {{ $member->status === 'Active'
                                                ? 'Deactivate'
                                                : 'Activate' }}

                                        </button>

                                    </form>

                                    <!-- DELETE -->

                                    <form
                                        action="{{ route('members.destroy', $member) }}"
                                        method="POST"
                                        onsubmit="return confirm('Permanently delete this member?');">

                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                                class="btn btn-red">
                                            Delete
                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="8" class="empty-table">
                                No members found matching your search or filters.
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        <!-- PAGINATION -->

        <div class="pagination">
            {{ $members->withQueryString()->links() }}
        </div>

    </div>

</div>

@endsection
