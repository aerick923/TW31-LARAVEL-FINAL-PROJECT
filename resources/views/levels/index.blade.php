
@extends('layouts.admin')

@section('title', 'Membership Levels - MemberHub')
@section('page-title', 'Membership Levels')

@push('styles')
<style>
    .levels-page {
        max-width: 1100px;
        margin: 0 auto;
        padding: 15px 0 40px;
    }

    .levels-page-heading {
        margin-bottom: 25px;
    }

    .levels-page-heading h1 {
        margin: 0 0 10px;
        color: #30236b;
        font-size: 27px;
        font-weight: 700;
    }

    .levels-page-heading p {
        margin: 0;
        color: #756b9e;
        font-size: 14px;
    }

    .levels-card {
        background: #ffffff;
        border: 1px solid #ddd6fe;
        border-radius: 14px;
        padding: 28px;
        box-shadow: 0 10px 30px rgba(48, 35, 107, 0.06);
    }

    .levels-card h2 {
        margin: 0 0 22px;
        color: #30236b;
        font-size: 21px;
    }

    .levels-toolbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
        margin-bottom: 20px;
    }

    .levels-page .levels-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 11px 16px;
        border: none;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
        cursor: pointer;
        transition: background 0.2s ease;
    }

    .levels-btn-gray {
        background: #495879;
        color: #ffffff;
    }

    .levels-btn-gray:hover {
        background: #364462;
        color: #ffffff;
    }

    .levels-btn-green {
        background: #318a6b;
        color: #ffffff;
    }

    .levels-btn-green:hover {
        background: #246c53;
        color: #ffffff;
    }

    .levels-btn-purple {
        background: #8768ef;
        color: #ffffff;
    }

    .levels-btn-purple:hover {
        background: #7052d7;
        color: #ffffff;
    }

    .levels-table-wrapper {
        width: 100%;
        overflow-x: auto;
        border: 1px solid #8b79dd;
        border-radius: 10px;
    }

    .levels-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 650px;
    }

    .levels-table th {
        padding: 15px 13px;
        background: #ede9fe;
        color: #30236b;
        font-size: 13px;
        font-weight: 700;
        text-align: left;
    }

    .levels-table td {
        padding: 15px 13px;
        border-top: 1px solid #e8e2ff;
        color: #28245e;
        font-size: 13px;
        vertical-align: middle;
    }

    .levels-table tbody tr:hover {
        background: #faf9ff;
    }

    .levels-fee {
        font-weight: 600;
        white-space: nowrap;
    }

    .levels-benefits {
        max-width: 350px;
        line-height: 1.6;
    }

    .levels-empty {
        text-align: center;
        padding: 30px !important;
        color: #756b9e !important;
    }

    .levels-alert-success {
        background: #d1fae5;
        color: #065f46;
        padding: 15px;
        border-radius: 9px;
        margin-bottom: 20px;
        font-size: 14px;
    }

    .levels-alert-error {
        background: #fee2e2;
        color: #991b1b;
        padding: 15px;
        border-radius: 9px;
        margin-bottom: 20px;
        font-size: 14px;
    }

    @media (max-width: 768px) {
        .levels-page {
            padding: 10px 0 30px;
        }

        .levels-page-heading h1 {
            font-size: 23px;
        }

        .levels-card {
            padding: 18px;
        }

        .levels-toolbar {
            align-items: stretch;
        }
    }
</style>
@endpush

@section('content')

<div class="levels-page">

    <div class="levels-page-heading">
        <h1>Membership Levels</h1>
        <p>
            Manage membership fees, validity periods, and benefits.
        </p>
    </div>

    <div class="levels-card">

        <h2>Membership Levels</h2>

        @if(session('success'))
            <div class="levels-alert-success" role="status">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="levels-alert-error" role="alert">
                {{ session('error') }}
            </div>
        @endif

        <div class="levels-toolbar">



            <a href="{{ route('levels.create') }}"
               class="levels-btn levels-btn-green">
                + Add Level
            </a>

        </div>

        <div class="levels-table-wrapper">

            <table class="levels-table">

                <thead>
                    <tr>
                        <th>Level</th>
                        <th>Fee</th>
                        <th>Validity</th>
                        <th>Benefits</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($levels as $level)

                        <tr>
                            <td>
                                {{ $level->name }}
                            </td>

                            <td class="levels-fee">
                                ₱{{ number_format($level->fee, 2) }}
                            </td>

                            <td>
                                @if($level->validity_months !== null)
                                    {{ $level->validity_months }} months
                                @else
                                    Not set
                                @endif
                            </td>

                            <td class="levels-benefits">
                                {{ $level->benefits ?? 'No benefits specified' }}
                            </td>

                            <td>
                                <a href="{{ route('levels.edit', $level) }}"
                                   class="levels-btn levels-btn-purple">
                                    Edit
                                </a>
                            </td>
                        </tr>

                    @empty

                        <tr>
                            <td colspan="5" class="levels-empty">
                                No membership levels found.
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection
