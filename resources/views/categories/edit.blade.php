
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Edit Membership Category</title>

    <link rel="stylesheet"
          href="{{ asset('css/design.css') }}">
</head>

<body>

<header>
    <h1>Edit Membership Category</h1>
    <p>Update membership category information</p>
</header>

<main class="container members-container">
    <div class="card">

        <h2>Edit Category: {{ $category->name }}</h2>

        @if($errors->any())
            <div class="error-box" role="alert">
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
                <label for="name">Category Name</label>

                <select id="name" name="name" required>
                    @foreach(['Student', 'Regular', 'Senior Citizen', 'Corporate'] as $name)
                        <option value="{{ $name }}"
                            @selected(old('name', $category->name) === $name)>
                            {{ $name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="level">Category Level (Optional)</label>

                <input type="text"
                       id="level"
                       name="level"
                       maxlength="255"
                       value="{{ old('level', $category->level) }}"
                       placeholder="e.g. Associate or Standard">
            </div>

            <div class="form-group">
                <label for="fee">Membership Fee (₱)</label>

                <input type="number"
                       id="fee"
                       name="fee"
                       min="0"
                       max="99999999.99"
                       step="0.01"
                       value="{{ old('fee', $category->fee) }}"
                       required>
            </div>

            <div class="form-group">
                <label for="is_active">Category Status</label>

                <select id="is_active" name="is_active" required>
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
</main>

</body>
</html>
