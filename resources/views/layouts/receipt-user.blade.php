
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Membership Payment Receipt')</title>

    <!-- Shared MemberHub Stylesheet -->
    <link rel="stylesheet"
          href="{{ asset('css/design.css') }}">

    <style>
        /* General Page Styling */

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: #f7f5ff;
            font-family: Arial, Helvetica, sans-serif;
        }

        /* Simple MemberHub Header */

        .receipt-user-header {
            background: #ffffff;
            border-bottom: 1px solid #e6dfff;
            padding: 20px 28px;
        }



.receipt-user-header-inner {
    max-width: 1100px;
    margin: 0 auto;
    padding: 0;
    text-align: left;
}



        .receipt-user-header h2 {
            margin: 0;
            color: #30236b;
            font-size: 22px;
            font-weight: 700;
        }

        /* Receipt Content */

        .receipt-user-content {
            max-width: 950px;
            margin: 0 auto;
            padding: 30px 25px;
        }

        /* Print Styling */

        @media print {
            .receipt-user-header {
                display: none !important;
            }

            .receipt-user-content {
                max-width: none;
                padding: 0;
            }

            body {
                background: #ffffff;
            }
        }
    </style>

    @stack('styles')
</head>

<body>

    <!-- Simple MemberHub Header -->
    <header class="receipt-user-header">
        <div class="receipt-user-header-inner">
            <h2>MemberHub</h2>
        </div>
    </header>

    <!-- Receipt Content -->
    <main class="receipt-user-content">
        @yield('content')
    </main>

    @stack('scripts')

</body>
</html>
