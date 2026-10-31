
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Register New Member</title>

    <link rel="stylesheet" href="{{ asset('css/design.css') }}">
</head>

<body>

<header>
    <h1>Register New Member</h1>
    <p>Enter the membership information below</p>
</header>

<main class="member-form-container">
    <div class="card">

        <h2>Member Information</h2>

        @if ($errors->any())
            <div class="error-box">
                Please correct the errors below.
            </div>
        @endif

        <form action="{{ route('members.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="name">Full Name</label>

                <input type="text"
                       id="name"
                       name="name"
                       value="{{ old('name') }}"
                       required>

                @error('name')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="email">Email Address</label>

                <input type="email"
                       id="email"
                       name="email"
                       value="{{ old('email') }}"
                       required>

                @error('email')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="phone">Phone Number</label>

                <input type="text"
                       id="phone"
                       name="phone"
                       value="{{ old('phone') }}">

                @error('phone')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="category">Membership Category</label>

                <select id="category" name="category" required>
                    <option value="">Select Category</option>

                    @foreach(['Student', 'Regular', 'Senior Citizen', 'Corporate'] as $category)
                        <option value="{{ $category }}"
                            {{ old('category') == $category ? 'selected' : '' }}>
                            {{ $category }}
                        </option>
                    @endforeach
                </select>

                @error('category')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            
<div class="form-group">
    <label for="level">Membership Level</label>

    <select id="level" name="level" required>
        <option value="">Select Level</option>

        @foreach($levels as $level)
            <option value="{{ $level->name }}"
                {{ old('level') == $level->name ? 'selected' : '' }}>
                {{ $level->name }} —
                ₱{{ number_format($level->fee, 2) }}
            </option>
        @endforeach
    </select>

    @error('level')
        <div class="error">{{ $message }}</div>
    @enderror
</div>


            <div class="member-form-actions">
                <button type="submit" class="btn">
                    Register Member
                </button>

                <a href="{{ route('members.index') }}"
                   class="btn btn-gray">
                    Cancel
                </a>
            </div>

        </form>

    </div>
</main>

</body>
</html>
