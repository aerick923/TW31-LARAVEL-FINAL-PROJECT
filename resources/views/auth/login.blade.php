<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Administrator Login</title>

    <link rel="stylesheet"
          href="{{ asset('css/design.css') }}">
</head>

<body>

<header>
    <h1>Administrator Login</h1>
    <p>Laravel Membership Management System</p>
</header>

<main class="container members-container">

    <div class="card" style="max-width: 500px; margin: 40px auto;">

        <h2>Sign In</h2>

        <p>Enter your administrator credentials to continue.</p>

        @if(session('success'))
            <div class="success" role="status">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="error-box" role="alert">
                @foreach($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form action="{{ route('login.submit') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="email">Email Address</label>

                <input type="email"
                       id="email"
                       name="email"
                       value="{{ old('email') }}"
                       autocomplete="username"
                       required>
            </div>

            <div class="form-group">
                <label for="password">Password</label>

                <input type="password"
                       id="password"
                       name="password"
                       autocomplete="current-password"
                       required>
            </div>

            <button type="submit" class="btn">
                Login
            </button>
        </form>

        <p style="margin-top: 20px;">
            <a href="{{ route('membership.create') }}">
                Back to Membership Application
            </a>
        </p>

    </div>

</main>

</body>
</html>
