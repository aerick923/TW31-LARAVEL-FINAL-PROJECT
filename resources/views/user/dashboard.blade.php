
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>User Dashboard</title>

    <link rel="stylesheet"
          href="{{ asset('css/design.css') }}">
</head>
<body>

<header class="hero">
    <h1>My Membership Dashboard</h1>
    <p>Laravel Membership Management System</p>
</header>

<main class="container">

    <div class="card"
         style="max-width: 900px; margin: 40px auto;">

        <h2>Welcome, {{ auth()->user()->name }}!</h2>

        @if (session('success'))
            <div style="
                background: #d1fae5;
                color: #065f46;
                padding: 15px;
                border-radius: 8px;
                margin: 20px 0;
            ">
                {{ session('success') }}
            </div>
        @endif

        <h3>My Account Information</h3>

        <p>
            <strong>Name:</strong>
            {{ auth()->user()->name }}
        </p>

        <p>
            <strong>Email:</strong>
            {{ auth()->user()->email }}
        </p>

        <p>
            <strong>Email Verification:</strong>
            {{ auth()->user()->hasVerifiedEmail()
                ? 'Verified'
                : 'Not Verified' }}
        </p>

        <p>
            <strong>Account Type:</strong>
            Regular User
        </p>

        <hr style="margin: 25px 0;">

        <h3>Membership Services</h3>

        <p>
            Your membership applications and account
            services will appear here.
        </p>

        <form action="{{ route('logout') }}"
              method="POST"
              style="margin-top: 25px;">

            @csrf

            <button type="submit" class="btn">
                Logout
            </button>

        </form>

    </div>

</main>

</body>
</html>
