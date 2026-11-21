
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Add Membership Level</title>

    <link rel="stylesheet"
          href="{{ asset('css/design.css') }}">
</head>

<body>

<header>
    <h1>Add Membership Level</h1>
    <p>Create a new membership level</p>
</header>

<main class="container members-container">
    <div class="card">

        <h2>Level Information</h2>

        @if($errors->any())
            <div class="error-box" role="alert">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('levels.store') }}"
              method="POST">

            @csrf

            <div class="form-group">
                <label for="name">Membership Level</label>

                <select id="name" name="name" required>
                    <option value="">Select a level</option>

                    @foreach(['Basic', 'Silver', 'Gold', 'Platinum'] as $name)
                        <option value="{{ $name }}"
                            @selected(old('name') === $name)>
                            {{ $name }}
                        </option>
                    @endforeach
                </select>
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
                <label for="validity_months">
                    Validity Period (Months)
                </label>

                <input type="number"
                       id="validity_months"
                       name="validity_months"
                       min="1"
                       max="120"
                       value="{{ old('validity_months', 12) }}"
                       required>
            </div>

            <div class="form-group">
                <label for="benefits">Membership Benefits</label>

                <textarea id="benefits"
                          name="benefits"
                          rows="4"
                          maxlength="5000"
                          placeholder="Describe membership benefits">{{ old('benefits') }}</textarea>
            </div>

            <div class="toolbar">
                <button type="submit" class="btn">
                    Save Level
                </button>

                <a href="{{ route('levels.index') }}"
                   class="btn btn-gray">
                    Cancel
                </a>
            </div>

        </form>

    </div>
</main>

</body>
</html>
