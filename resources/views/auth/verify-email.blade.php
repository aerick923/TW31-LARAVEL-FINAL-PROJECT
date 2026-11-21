
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Verify Email Address</title>

    <link rel="stylesheet"
          href="{{ asset('css/design.css') }}">
</head>
<body>

<header class="hero">
    <h1>Verify Your Email</h1>
    <p>Laravel Membership Management System</p>
</header>

<main class="container">

    <div class="card"
         style="max-width: 550px; margin: 40px auto;">

        <h2>Email Verification Required</h2>

        <p>
            Thank you for creating an account!
        </p>

        <p>
            We have sent a verification link to:
        </p>

        <p>
            <strong>{{ auth()->user()->email }}</strong>
        </p>

        <p>
            Please check your email inbox and click
            <strong>Verify Email Address</strong>
            to activate your account.
        </p>

        <p>
            You must verify your email before accessing
            your membership dashboard.
        </p>

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

        <form action="{{ route('verification.send') }}"
              method="POST"
              style="margin-top: 25px;">

            @csrf

            <button type="submit" class="btn">
                Resend Verification Email
            </button>

        </form>

        <form action="{{ route('logout') }}"
              method="POST"
              style="margin-top: 15px;">

            @csrf

            <button type="submit"
                    class="btn btn-gray">
                Logout
            </button>

        </form>

    </div>

</main>

</body>
</html>
