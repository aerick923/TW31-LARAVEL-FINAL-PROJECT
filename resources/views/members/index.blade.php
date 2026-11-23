
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
        margin-bottom: 22px;
        color: #30236b;
    }

    .members-page .toolbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
        margin-bottom: 25px;
    }

    .members-page .toolbar form {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 8px;
    }

    .members-page .toolbar input {
        min-width: 230px;
        padding: 12px;
        border: 1px solid #dcd3ff;
        border-radius: 8px;
    }

    .members-page .table-wrapper {
        width: 100%;
        overflow-x: auto;
    }

    .members-page table {
        width: 100%;
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

    .members-page .pagination {
        margin-top: 22px;
    }

    @media (max-width: 768px) {
        .members-page .card {
            padding: 18px;
        }

        .members-page .toolbar form {
            width: 100%;
        }

        .members-page .toolbar input {
            min-width: 0;
            flex: 1;
        }
    }
</style>
@endpush

@section('content')

<div class="members-page">

    <div class="card">

        <h2>Registered Members</h2>

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

        <!-- SEARCH AND ADD MEMBER -->

        <div class="toolbar">

            <form action="{{ route('members.index') }}"
                  method="GET">

                <input
                    type="text"
                    name="search"
                    placeholder="Search name, category, or level"
                    value="{{ request('search') }}"
                    aria-label="Search members"
                >

                <button type="submit" class="btn">
                    Search
                </button>

                <a href="{{ route('members.index') }}"
                   class="btn btn-gray">
                    Clear
                </a>

            </form>

            <a href="{{ route('members.create') }}"
               class="btn btn-green">
                + Add New Member
            </a>

        </div>

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
                                        $member->membershipLevel->fee, 2
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

                                    <a href="{{ route('members.show', $member) }}"
                                       class="btn">
                                        View
                                    </a>

                                    <a href="{{ route('members.edit', $member) }}"
                                       class="btn btn-orange">
                                        Edit
                                    </a>

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
                                No members found.
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
