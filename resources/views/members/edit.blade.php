
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Edit Member</title>

    <link rel="stylesheet" href="{{ asset('css/design.css') }}">
</head>

<body>

<header>
    <h1>Edit Member</h1>
    <p>Update existing membership information</p>
</header>

<main class="member-form-container">
    <div class="card">

        <h2>Member Information</h2>

        @if ($errors->any())
            <div class="error-box">
                Please correct the errors below.
            </div>
        @endif

        <form action="{{ route('members.update', $member) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="membership_number">
                    Membership Number
                </label>

                <input type="text"
                       id="membership_number"
                       value="{{ $member->membership_number }}"
                       readonly>
            </div>

            <div class="form-group">
                <label for="name">Full Name</label>

                <input type="text"
                       id="name"
                       name="name"
                       value="{{ old('name', $member->name) }}"
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
                       value="{{ old('email', $member->email) }}"
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
                       value="{{ old('phone', $member->phone) }}">

                @error('phone')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="category">Membership Category</label>

                <select id="category" name="category" required>
                    @foreach(['Student', 'Regular', 'Senior Citizen', 'Corporate'] as $category)
                        <option value="{{ $category }}"
                            {{ old('category', $member->category) == $category ? 'selected' : '' }}>
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

        @foreach($levels as $level)
            <option value="{{ $level->name }}"
                {{ old('level', $member->level) == $level->name
                    ? 'selected'
                    : '' }}>
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
                    Save Changes
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
