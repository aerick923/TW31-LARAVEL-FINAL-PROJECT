
@extends('layouts.admin')

@section('title', 'Add Membership Category')
@section('page-title', 'Add Membership Category')

@push('styles')
<style>
    .category-create-page {
        max-width: 1050px;
        margin: 0 auto;
    }

    .category-create-page .page-heading {
        margin-bottom: 24px;
    }

    .category-create-page .page-heading h1 {
        color: #30236b;
        font-size: 26px;
        margin: 0 0 10px;
    }

    .category-create-page .page-heading p {
        color: #7568a5;
        font-size: 14px;
        margin: 0;
    }

    .category-create-page .category-card {
        background: #fff;
        border: 1px solid #ded7ff;
        border-radius: 14px;
        padding: 28px;
        box-shadow: 0 8px 24px rgba(48, 35, 107, 0.05);
    }

    .category-create-page .category-card h2 {
        color: #30236b;
        font-size: 21px;
        margin: 0 0 24px;
    }

    .category-create-page .form-group {
        margin-bottom: 22px;
    }

    .category-create-page .form-group label {
        display: block;
        font-weight: 600;
        margin-bottom: 9px;
        color: #30236b;
    }

    .category-create-page .form-group input,
    .category-create-page .form-group select,
    .category-create-page .form-group textarea {
        display: block;
        width: 100%;
        max-width: 100%;
        min-width: 0;
        box-sizing: border-box;
        padding: 13px 14px;
        border: 1px solid #dcd4ff;
        border-radius: 8px;
        background: #fff;
        color: #30236b;
        font: inherit;
    }

    .category-create-page .form-group textarea {
        min-height: 120px;
        resize: vertical;
    }

    .category-create-page .form-group input:focus,
    .category-create-page .form-group select:focus,
    .category-create-page .form-group textarea:focus {
        outline: 2px solid #a897ff;
        outline-offset: 1px;
    }

    .category-create-page .form-help {
        margin-top: 7px;
        font-size: 12px;
        color: #7568a5;
    }

    .category-create-page .form-actions {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-top: 25px;
    }

    @media (max-width: 768px) {
        .category-create-page .category-card {
            padding: 20px;
        }

        .category-create-page .form-actions {
            flex-wrap: wrap;
        }
    }
</style>
@endpush

@section('content')

<div class="category-create-page">

    <div class="page-heading">
        <h1>Add Membership Category</h1>
        <p>Create a new membership category.</p>
    </div>

    <div class="category-card">

        <h2>Category Information</h2>

        @if($errors->any())
            <div class="error-box" role="alert">
                <strong>Please correct the following errors:</strong>
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('categories.store') }}"
              method="POST">

            @csrf

            <div class="form-group">
                <label for="name">Category Name</label>

                <input type="text"
                       id="name"
                       name="name"
                       value="{{ old('name') }}"
                       maxlength="255"
                       placeholder="Enter membership category name"
                       required>

                <p class="form-help">
                    Enter a unique category name.
                </p>
            </div>

            <div class="form-group">
                <label for="level">
                    Category Level Label (Optional)
                </label>

                <input type="text"
                       id="level"
                       name="level"
                       value="{{ old('level') }}"
                       maxlength="255"
                       placeholder="For example: Standard or Associate">

                <p class="form-help">
                    This is an optional category label, not
                    the paid membership level.
                </p>
            </div>

            <div class="form-group">
                <label for="is_active">Category Status</label>

                <select id="is_active"
                        name="is_active"
                        required>

                    <option value="1"
                        @selected(old('is_active', '1') == '1')>
                        Active
                    </option>

                    <option value="0"
                        @selected(old('is_active', '1') == '0')>
                        Inactive
                    </option>

                </select>
            </div>

            <div class="form-group">
                <label for="description">Description</label>

                <textarea id="description"
                          name="description"
                          rows="4"
                          maxlength="5000"
                          placeholder="Describe this membership category">{{ old('description') }}</textarea>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn">
                    Save Category
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
