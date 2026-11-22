
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>User Registration</title>

    <link rel="stylesheet"
          href="{{ asset('css/design.css') }}">

    <style>
        /* Registration page layout */
        .registration-card {
            width: 100%;
            max-width: 550px;
            margin: 40px auto;
            box-sizing: border-box;
        }

        .registration-form {
            width: 100%;
        }

        /* Form field spacing */
        .registration-form .form-group {
            width: 100%;
            margin-bottom: 20px;
        }

        /* Form labels */
        .registration-form label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
        }

        /* Full-width input fields */
        .registration-form input {
            display: block;
            width: 100%;
            max-width: none;
            min-width: 0;
            box-sizing: border-box;
            padding: 12px 14px;
        }

        /* Password instructions */
        .registration-form .password-help {
            display: block;
            width: 100%;
            margin-top: 8px;
            font-size: 13px;
            line-height: 1.6;
            color: #6b7280;
        }

        /* Full-width registration button */
        .registration-form .btn {
            display: block;
            width: 100%;
            box-sizing: border-box;
            text-align: center;
            cursor: pointer;
        }

        /* Validation error message */
        .registration-errors {
            background: #fee2e2;
            color: #991b1b;
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
        }

        .registration-errors ul {
            margin-bottom: 0;
            padding-left: 20px;
        }

        /* Login navigation */
        .registration-login {
            margin-top: 25px;
            text-align: center;
        }

        /* Mobile responsiveness */
        @media (max-width: 600px) {
            .registration-card {
                margin: 20px auto;
            }
        }
    </style>
</head>

<body>

<header class="hero">
    <h1>Create Your Account</h1>
    <p>Laravel Membership Management System</p>
</header>

<main class="container">

    <div class="card registration-card">

        <h2>User Registration</h2>

        <p>
            Create an account to apply for membership.
        </p>

        <!-- VALIDATION ERRORS -->

        @if ($errors->any())
            <div class="registration-errors" role="alert">
                <strong>
                    Please correct the following errors:
                </strong>

                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- REGISTRATION FORM -->

        <form action="{{ route('register.submit') }}"
              method="POST"
              class="registration-form">

            @csrf

            <!-- FULL NAME -->

            <div class="form-group">

                <label for="name">
                    Full Name
                </label>

                <input
                    type="text"
                    id="name"
                    name="name"
                    value="{{ old('name') }}"
                    required
                    maxlength="255"
                    autocomplete="name"
                >

            </div>

            <!-- EMAIL ADDRESS -->

            <div class="form-group">

                <label for="email">
                    Email Address
                </label>

                <input
                    type="email"
                    id="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    maxlength="255"
                    autocomplete="email"
                >

            </div>

            <!-- PASSWORD -->

            <div class="form-group">

                <label for="password">
                    Password
                </label>

                <input
                    type="password"
                    id="password"
                    name="password"
                    required
                    minlength="8"
                    autocomplete="new-password"
                    aria-describedby="password-help"
                >

                <small
                    id="password-help"
                    class="password-help">
                    Use at least 8 characters, including
                    uppercase letters, lowercase letters,
                    and numbers.
                </small>

            </div>

            <!-- CONFIRM PASSWORD -->

            <div class="form-group">

                <label for="password_confirmation">
                    Confirm Password
                </label>

                <input
                    type="password"
                    id="password_confirmation"
                    name="password_confirmation"
                    required
                    minlength="8"
                    autocomplete="new-password"
                >

            </div>

            <!-- SUBMIT BUTTON -->

            <button type="submit" class="btn">
                Create Account
            </button>

        </form>

        <!-- LOGIN NAVIGATION -->

        <p class="registration-login">
            Already have an account?
            <a href="{{ route('login') }}">
                Login here
            </a>
        </p>

    </div>

</main>

</body>
</html>
