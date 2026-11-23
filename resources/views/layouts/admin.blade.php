
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'MemberHub Administration')</title>

    <link rel="stylesheet"
          href="{{ asset('css/design.css') }}">

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: #f7f5ff;
        }

        .admin-layout {
            display: flex;
            min-height: 100vh;
        }

        .admin-sidebar {
            width: 250px;
            flex-shrink: 0;
            background: linear-gradient(
                180deg, #30266b, #493887
            );
            color: white;
            padding: 24px 15px;
            display: flex;
            flex-direction: column;
        }

        .admin-brand {
            padding: 8px 12px 25px;
            border-bottom: 1px solid #66579b;
        }

        .admin-brand h2 {
            color: white;
            margin: 0;
            font-size: 23px;
        }

        .admin-brand p {
            color: #d5cbff;
            font-size: 12px;
            margin: 5px 0 0;
        }

        .sidebar-label {
            color: #c8bbff;
            font-size: 11px;
            letter-spacing: 2px;
            margin: 25px 12px 12px;
        }

        .admin-nav {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .admin-nav a {
            display: block;
            color: white;
            text-decoration: none;
            padding: 13px 14px;
            border-radius: 9px;
            font-size: 14px;
            font-weight: 600;
        }

        .admin-nav a:hover,
        .admin-nav a.active {
            background: #7460d4;
        }

        .sidebar-logout {
            margin-top: auto;
            padding-top: 25px;
        }

        .sidebar-logout button {
            width: 100%;
            padding: 12px;
            border: 1px solid #8170b7;
            border-radius: 9px;
            background: transparent;
            color: white;
            cursor: pointer;
            font-weight: 600;
        }

        .sidebar-logout button:hover {
            background: #7460d4;
        }

        .admin-main {
            flex: 1;
            min-width: 0;
        }

        .admin-topbar {
            background: white;
            padding: 22px 35px;
            border-bottom: 1px solid #e6dfff;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .admin-topbar h1 {
            font-size: 20px;
            color: #30236b;
            margin: 0;
        }

        .admin-topbar span {
            color: #665a91;
            font-size: 14px;
        }

        .admin-content {
            max-width: 1250px;
            margin: 0 auto;
            padding: 35px;
        }

        .admin-content .card {
            max-width: none;
        }

        .admin-menu-toggle {
            display: none;
            border: 1px solid #ddd6fe;
            background: #f5f2ff;
            color: #30236b;
            border-radius: 8px;
            padding: 9px 12px;
            cursor: pointer;
        }

        @media (max-width: 768px) {
            .admin-layout {
                display: block;
            }

            .admin-sidebar {
                display: none;
                width: 100%;
            }

            .admin-sidebar.open {
                display: flex;
            }

            .admin-menu-toggle {
                display: inline-block;
            }

            .admin-topbar {
                padding: 18px;
                gap: 12px;
            }

            .admin-content {
                padding: 18px;
            }
        }
    </style>

    @stack('styles')
</head>

<body>

<div class="admin-layout">

    <aside class="admin-sidebar" id="adminSidebar">

        <div class="admin-brand">
            <h2>MemberHub</h2>
            <p>Administration Workspace</p>
        </div>

        <div class="sidebar-label">WORKSPACE</div>

        <nav class="admin-nav"
             aria-label="Administrator navigation">

            <a href="{{ route('dashboard.index') }}"
               class="{{ request()->routeIs('dashboard.*') ? 'active' : '' }}">
                Dashboard
            </a>

            <a href="{{ route('members.index') }}"
               class="{{ request()->routeIs('members.*') ? 'active' : '' }}">
                Members
            </a>

            <a href="{{ route('admin.applications.index') }}"
               class="{{ request()->routeIs('admin.applications.*') ? 'active' : '' }}">
                Applications
            </a>

            <a href="{{ route('categories.index') }}"
               class="{{ request()->routeIs('categories.*') ? 'active' : '' }}">
                Categories
            </a>

            <a href="{{ route('levels.index') }}"
               class="{{ request()->routeIs('levels.*') ? 'active' : '' }}">
                Levels
            </a>

            <a href="{{ route('transactions.index') }}"
               class="{{ request()->routeIs('transactions.*') ? 'active' : '' }}">
                Transactions
            </a>

        </nav>

        <div class="sidebar-logout">
            <form action="{{ route('logout') }}"
                  method="POST">
                @csrf

                <button type="submit">
                    Logout
                </button>
            </form>
        </div>

    </aside>

    <div class="admin-main">

        <div class="admin-topbar">

            <button type="button"
                    class="admin-menu-toggle"
                    aria-controls="adminSidebar"
                    aria-expanded="false"
                    onclick="toggleAdminSidebar(this)">
                Menu
            </button>

            <h1>@yield('page-title', 'Dashboard')</h1>

            <span>{{ auth()->user()->name }}</span>

        </div>

        <main class="admin-content">
            @yield('content')
        </main>

    </div>

</div>

<script>
    function toggleAdminSidebar(button) {
        const sidebar = document.getElementById('adminSidebar');

        const isOpen = sidebar.classList.toggle('open');

        button.setAttribute('aria-expanded', String(isOpen));
    }
</script>

@stack('scripts')

</body>
</html>
