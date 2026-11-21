
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>User Registration</title>

    <link rel="stylesheet"
          href="{{ asset('css/design.css') }}">
</head>
<body>

<header class="hero">
    <h1>Create Your Account</h1>
    <p>Laravel Membership Management System</p>
</header>

<main class="container">
    <div class="card" style="max-width: 550px; margin: 40px auto;">

        <h2>User Registration</h2>
        <p>Create an account to apply for membership.</p>

        @if ($errors->any())
            <div style="color: #b91c1c; margin: 20px 0;">
                <strong>Please correct the following errors:</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('register.submit') }}"
              method="POST">

            @csrf

            <div style="margin-bottom: 20px;">
                <label for="name">Full Name</label>

                <input type="text"
                       id="name"
                       name="name"
                       value="{{ old('name') }}"
                       required
                       maxlength="255"
                       autocomplete="name">
            </div>

            <div style="margin-bottom: 20px;">
                <label for="email">Email Address</label>

                <input type="email"
                       id="email"
                       name="email"
                       value="{{ old('email') }}"
                       required
                       maxlength="255"
                       autocomplete="email">
            </div>

            <div style="margin-bottom: 20px;">
                <label for="password">Password</label>

                <input type="password"
                       id="password"
                       name="password"
                       required
                       minlength="8"
                       autocomplete="new-password">

                <small>
                    Use at least 8 characters, including uppercase,
                    lowercase, and numbers.
                </small>
            </div>

            <div style="margin-bottom: 20px;">
                <label for="password_confirmation">
                    Confirm Password
                </label>

                <input type="password"
                       id="password_confirmation"
                       name="password_confirmation"
                       required
                       minlength="8"
                       autocomplete="new-password">
            </div>

            <button type="submit" class="btn">
                Create Account
            </button>

        </form>

        <p style="margin-top: 25px;">
            Already have an account?
            <a href="{{ route('login') }}">Login here</a>
        </p>

    </div>
</main>

</body>
</html>
