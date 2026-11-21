
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Add Membership Category</title>

    <link rel="stylesheet"
          href="{{ asset('css/design.css') }}">
</head>

<body>

<header>
    <h1>Add Membership Category</h1>
    <p>Create a new membership category</p>
</header>

<main class="container members-container">
    <div class="card">

        <h2>Category Information</h2>

        @if($errors->any())
            <div class="error-box" role="alert">
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

                <select id="name" name="name" required>
                    <option value="">Select a category</option>

                    @foreach(['Student', 'Regular', 'Senior Citizen', 'Corporate'] as $name)
                        <option value="{{ $name }}"
                            @selected(old('name') === $name)>
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
                       value="{{ old('level') }}"
                       maxlength="255"
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
                       value="{{ old('fee', '0.00') }}"
                       required>
            </div>

            <div class="form-group">
                <label for="is_active">Category Status</label>

                <select id="is_active" name="is_active" required>
                    <option value="1"
                        @selected(old('is_active', '1') == '1')>
                        Active
                    </option>

                    <option value="0"
                        @selected(old('is_active') == '0')>
                        Inactive
                    </option>
                </select>
            </div>

            <div class="toolbar">
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
</main>

</body>
</html>
