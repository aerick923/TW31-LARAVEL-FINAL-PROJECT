
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Login | Membership Management</title>

    <link rel="stylesheet"
          href="{{ asset('css/design.css') }}">

    <style>
        .login-card {
            max-width: 500px;
            margin: 40px auto;
            padding: 35px;
        }

        .login-description {
            margin-bottom: 25px;
            color: #555;
        }

        .login-card .form-group {
            margin-bottom: 20px;
        }

        .login-card label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
        }

        .login-card input {
            display: block;
            width: 100%;
            box-sizing: border-box;
            padding: 12px;
            border: 1px solid #d7d1f2;
            border-radius: 8px;
            font: inherit;
        }

        .login-card input:focus {
            outline: 2px solid #8b6cf0;
            outline-offset: 1px;
        }

        .login-button {
            width: 100%;
            margin-top: 10px;
            cursor: pointer;
        }

        .register-section {
            text-align: center;
            margin-top: 25px;
        }

        .register-section a {
            color: #7655d8;
            font-weight: 600;
        }

        .login-footer {
            text-align: center;
            margin-top: 20px;
        }

        .login-footer a {
            color: #7655d8;
        }

        .login-card .success {
            margin-bottom: 20px;
        }

        .login-card .error-box {
            margin-bottom: 20px;
        }




    </style>
</head>

<body>

<header class="hero">
    <h1>Welcome Back!</h1>
    <p>Laravel Membership Management System</p>
</header>

<main class="container members-container">

    <div class="card login-card">

        <h2>Login to Your Account</h2>

        <p class="login-description">
            Sign in using your registered email address
            and password.
        </p>

        @if (session('success'))
            <div class="success" role="status">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="error-box" role="alert">
                <strong>Login failed.</strong>

                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form action="{{ route('login.submit') }}"
              method="POST">

            @csrf

            <div class="form-group">
                <label for="email">
                    Email Address
                </label>

                <input
                    type="email"
                    id="email"
                    name="email"
                    value="{{ old('email') }}"
                    placeholder="Enter your email address"
                    autocomplete="username"
                    maxlength="255"
                    required
                    autofocus>
            </div>

            <div class="form-group">
                <label for="password">
                    Password
                </label>

                <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="Enter your password"
                    autocomplete="current-password"
                    required>
            </div>

            <button type="submit"
                    class="btn login-button">
                Login
            </button>

        </form>





        <div class="register-section">
            <p>
                Don't have an account?
                <a href="{{ route('register') }}">
                    Create an Account
                </a>
            </p>
        </div>

        

    </div>

</main>

</body>
</html>
