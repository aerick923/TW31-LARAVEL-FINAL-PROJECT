
@extends('layouts.admin')

@section('title', 'Edit Membership Category')
@section('page-title', 'Edit Membership Category')

@push('styles')
<style>
    .category-edit-page {
        max-width: 1050px;
        margin: 0 auto;
    }

    .category-edit-page .page-heading {
        margin-bottom: 24px;
    }

    .category-edit-page .page-heading h1 {
        color: #30236b;
        margin-bottom: 10px;
    }

    .category-edit-page .page-heading p {
        color: #7668a5;
        margin: 0;
    }

    .category-edit-page .card {
        background: white;
        border: 1px solid #ddd6fe;
        border-radius: 14px;
        padding: 28px;
    }

    .category-edit-page .card h2 {
        margin: 0 0 24px;
        color: #30236b;
    }

    .category-edit-page .form-group {
        margin-bottom: 22px;
    }

    .category-edit-page .form-group label {
        display: block;
        margin-bottom: 9px;
        font-weight: 600;
        color: #30236b;
    }

    .category-edit-page .form-group input,
    .category-edit-page .form-group select,
    .category-edit-page .form-group textarea {
        display: block;
        width: 100%;
        max-width: 100%;
        box-sizing: border-box;
        padding: 13px 14px;
        border: 1px solid #ddd6fe;
        border-radius: 8px;
        background: white;
        color: #30236b;
        font: inherit;
    }

    .category-edit-page .form-group textarea {
        min-height: 120px;
        resize: vertical;
    }

    .category-edit-page .form-group input:focus,
    .category-edit-page .form-group select:focus,
    .category-edit-page .form-group textarea:focus {
        outline: 2px solid #a78bfa;
        outline-offset: 1px;
    }

    .category-edit-page .toolbar {
        display: flex;
        justify-content: flex-start;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
        margin-top: 25px;
    }

    .category-edit-page .toolbar .btn {
        width: auto;
    }
</style>
@endpush

@section('content')

<div class="category-edit-page">

    <div class="page-heading">
        <h1>Edit Membership Category</h1>
        <p>
            Update membership category information
            and description.
        </p>
    </div>

    <div class="card">

        <h2>Edit Category: {{ $category->name }}</h2>

        @if($errors->any())
            <div class="error-box" role="alert">
                <strong>
                    Please correct the following errors:
                </strong>

                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('categories.update', $category) }}"
              method="POST">

            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="name">
                    Category Name
                </label>

                <input type="text"
                       id="name"
                       name="name"
                       maxlength="255"
                       value="{{ old('name', $category->name) }}"
                       placeholder="Enter membership category name"
                       required>
            </div>

            <div class="form-group">
                <label for="level">
                    Category Level Label (Optional)
                </label>

                <input type="text"
                       id="level"
                       name="level"
                       maxlength="255"
                       value="{{ old('level', $category->level) }}"
                       placeholder="For example: Standard or Associate">

                <small>
                    This is an optional category label,
                    not the paid membership level.
                </small>
            </div>

            <div class="form-group">
                <label for="is_active">
                    Category Status
                </label>

                <select id="is_active"
                        name="is_active"
                        required>

                    <option value="1"
                        @selected(old('is_active', $category->is_active) == '1')>
                        Active
                    </option>

                    <option value="0"
                        @selected(old('is_active', $category->is_active) == '0')>
                        Inactive
                    </option>

                </select>
            </div>

            <div class="form-group">
                <label for="description">
                    Description
                </label>

                <textarea id="description"
                          name="description"
                          rows="4"
                          maxlength="5000"
                          placeholder="Describe this membership category">{{ old('description', $category->description) }}</textarea>
            </div>

            <div class="toolbar">

                <button type="submit" class="btn">
                    Update Category
                </button>

                <a href="{{ route('categories.index') }}"
                   class="btn btn-gray">
                    Cancel
                </a>

            </div>

        </form>

    </div>

</div>

@endsection
