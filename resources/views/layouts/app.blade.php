<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Razorpay Store')</title>
    <meta name="description" content="Shop premium tech products and pay instantly with Razorpay.">

    {{-- Bootstrap 5 CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        /* ── Base / Typography ─────────────────────────────── */
        *, *::before, *::after { box-sizing: border-box; }

        /* ── Design System Variables ───────────────────────── */
        :root {
            --bg-page: #0b0f19;
            --bg-card: #131c2e;
            --bg-card-header: #1a253d;
            --bg-input: #1a253d;
            --border-color: rgba(255, 255, 255, 0.08);
            --border-focus: #3b82f6;
            --text-main: #f8fafc;
            --text-sub: #94a3b8;
            --accent-blue: #3b82f6;
            --accent-indigo: #6366f1;
            --accent-green: #10b981;
            --accent-red: #ef4444;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            font-size: 15px;
            line-height: 1.6;
            background: var(--bg-page);
            color: var(--text-main);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            -webkit-font-smoothing: antialiased;
        }

        h1, h2, h3, h4, h5, h6, .text-dark {
            font-family: 'Inter', sans-serif;
            font-weight: 800;
            letter-spacing: -0.4px;
            color: var(--text-main) !important;
        }

        .text-muted, .text-secondary {
            color: var(--text-sub) !important;
        }

        /* ── Navbar ────────────────────────────────────────── */
        .app-nav {
            background: rgba(11, 15, 25, 0.92);
            backdrop-filter: blur(14px);
            border-bottom: 1px solid var(--border-color);
            padding: 0.75rem 0;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 4px 20px rgba(0,0,0,0.4);
        }

        .app-nav-inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
        }

        .nav-brand {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            text-decoration: none;
            font-size: 1.05rem;
            font-weight: 800;
            color: #f8fafc;
            letter-spacing: -0.3px;
        }
        .nav-brand:hover { color: #60a5fa; }
        .nav-brand-icon {
            width: 34px; height: 34px;
            background: linear-gradient(135deg, #3b82f6, #6366f1);
            border-radius: 9px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }
        .nav-link-item {
            font-size: 0.88rem;
            font-weight: 600;
            color: var(--text-sub);
            text-decoration: none;
            padding: 0.45rem 0.85rem;
            border-radius: 8px;
            transition: background 0.18s, color 0.18s;
        }
        .nav-link-item:hover,
        .nav-link-item.active { color: #60a5fa; background: rgba(59, 130, 246, 0.15); }

        .nav-cart-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            background: var(--bg-card);
            border: 1.5px solid var(--border-color);
            color: #f1f5f9;
            font-size: 0.85rem;
            font-weight: 700;
            padding: 0.48rem 1rem;
            border-radius: 10px;
            text-decoration: none;
            position: relative;
            transition: all 0.18s;
        }
        .nav-cart-btn:hover { border-color: #60a5fa; color: #60a5fa; background: rgba(59, 130, 246, 0.2); }

        .nav-cart-dot {
            position: absolute;
            top: -5px; right: -5px;
            background: #ef4444;
            color: #fff;
            font-size: 0.65rem;
            font-weight: 800;
            width: 18px; height: 18px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid var(--bg-page);
        }

        .nav-user-chip {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            color: #f1f5f9;
            font-size: 0.82rem;
            font-weight: 600;
            padding: 0.42rem 0.9rem;
            border-radius: 8px;
        }

        .nav-logout-btn {
            font-size: 0.82rem;
            font-weight: 700;
            color: #f87171;
            background: transparent;
            border: 1.5px solid rgba(239, 68, 68, 0.4);
            padding: 0.42rem 0.9rem;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.18s;
        }
        .nav-logout-btn:hover { background: rgba(239, 68, 68, 0.2); border-color: #ef4444; color: #fff; }

        .nav-login-btn {
            font-size: 0.85rem;
            font-weight: 700;
            background: linear-gradient(135deg, #3b82f6, #6366f1);
            color: #fff;
            border: none;
            padding: 0.48rem 1.2rem;
            border-radius: 9px;
            text-decoration: none;
            transition: opacity 0.18s, transform 0.18s;
            box-shadow: 0 2px 8px rgba(99,102,241,0.3);
        }
        .nav-login-btn:hover { opacity:0.9; transform:translateY(-1px); color:#fff; }

        /* ── Main Content ──────────────────────────────────── */
        .main-content {
            flex: 1;
            padding-top: 2rem;
            padding-bottom: 3.5rem;
        }

        /* ── Global Card & Container Overrides ──────────────── */
        .card, .bg-white, .bg-light {
            background: var(--bg-card) !important;
            border-color: var(--border-color) !important;
            color: var(--text-main) !important;
        }
        .card-header, .table-light, .table-light th {
            background: var(--bg-card-header) !important;
            border-color: var(--border-color) !important;
            color: var(--text-main) !important;
        }
        .card-footer {
            background: var(--bg-card-header) !important;
            border-color: var(--border-color) !important;
            color: var(--text-main) !important;
        }
        .border-bottom, .border-top, .border {
            border-color: var(--border-color) !important;
        }

        /* Tables */
        .table {
            --bs-table-bg: var(--bg-card) !important;
            --bs-table-color: var(--text-main) !important;
            --bs-table-hover-bg: #1c273c !important;
            --bs-table-hover-color: var(--text-main) !important;
            --bs-table-striped-bg: #162033 !important;
            --bs-table-striped-color: var(--text-main) !important;
            color: var(--text-main) !important;
            background-color: var(--bg-card) !important;
        }
        .table > :not(caption) > * > * {
            background-color: var(--bg-card) !important;
            color: var(--text-main) !important;
            border-bottom-color: var(--border-color) !important;
        }
        .table-hover > tbody > tr:hover > * {
            background-color: #1c273c !important;
            color: var(--text-main) !important;
        }
        .table h1, .table h2, .table h3, .table h4, .table h5, .table h6, .table span, .table strong {
            color: #ffffff !important;
        }

        /* Alerts */
        .alert {
            border: none;
            border-radius: 12px;
            font-size: 0.9rem;
            font-weight: 500;
            padding: 0.85rem 1.25rem;
        }
        .alert-success {
            background: rgba(16, 185, 129, 0.15) !important;
            color: #34d399 !important;
            border-left: 4px solid #10b981 !important;
        }
        .alert-danger {
            background: rgba(239, 68, 68, 0.15) !important;
            color: #fca5a5 !important;
            border-left: 4px solid #ef4444 !important;
        }

        /* Buttons */
        .btn {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            font-weight: 600;
            border-radius: 9px;
        }
        .btn-primary {
            background: linear-gradient(135deg, #3b82f6, #6366f1);
            border: none;
            font-weight: 700;
            box-shadow: 0 3px 10px rgba(99,102,241,0.3);
            color: #fff !important;
        }
        .btn-primary:hover {
            background: linear-gradient(135deg, #2563eb, #4f46e5);
            box-shadow: 0 5px 16px rgba(99,102,241,0.4);
            color: #fff !important;
        }
        .btn-outline-secondary {
            color: var(--text-sub) !important;
            border-color: var(--border-color) !important;
        }
        .btn-outline-secondary:hover {
            background: rgba(255, 255, 255, 0.1) !important;
            color: #fff !important;
            border-color: rgba(255, 255, 255, 0.2) !important;
        }

        /* Form Controls */
        .form-control, .form-select {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            font-size: 0.92rem;
            border-radius: 10px;
            border: 1.5px solid var(--border-color) !important;
            padding: 0.65rem 0.95rem;
            color: var(--text-main) !important;
            background: var(--bg-input) !important;
            transition: border-color 0.18s, box-shadow 0.18s;
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--border-focus) !important;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.25) !important;
        }
        .form-control::placeholder, .form-select::placeholder {
            color: #64748b !important;
            opacity: 1;
        }
        .form-label {
            font-weight: 600;
            font-size: 0.85rem;
            color: var(--text-sub);
            margin-bottom: 0.4rem;
        }

        /* Footer */
        .app-footer {
            background: #070a12;
            border-top: 1px solid var(--border-color);
            padding: 1.4rem 0;
            text-align: center;
            font-size: 0.82rem;
            color: var(--text-sub);
            font-weight: 500;
            margin-top: auto;
        }

        /* ── Auth pages: full-screen split layout ──────────── */
        .auth-layout {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #f8fafc 0%, #eff6ff 100%);
            padding: 2rem 1rem;
        }
        .auth-card-wrap {
            width: 100%;
            max-width: 440px;
        }
        .auth-card {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 20px;
            box-shadow: 0 8px 40px rgba(0,0,0,0.08);
            padding: 2.5rem 2.5rem 2rem;
        }

        /* ── Section eyebrow label ─────────────────────────── */
        .section-eyebrow {
            display: inline-block;
            background: linear-gradient(135deg, #ede9fe, #dbeafe);
            color: #4f46e5;
            border-radius: 50px;
            padding: 0.28rem 1rem;
            font-size: 0.78rem;
            font-weight: 700;
            letter-spacing: 0.4px;
            margin-bottom: 0.7rem;
        }

        /* ── Badge / pill stock ────────────────────────────── */
        .badge { font-family: 'Inter', sans-serif; font-weight: 700; }
        .badge-stock { font-size: 0.75rem; padding: 0.3em 0.65em; border-radius: 6px; }
        .price-tag { font-size: 1.4rem; font-weight: 800; color: #059669; }
    </style>
</head>
<body>

    {{-- ── Navbar (hidden on auth pages) ─────────────────── --}}
    @unless(request()->routeIs('login','register','admin.login','password.request','password.reset','admin.password.request','admin.password.reset'))
    <nav class="app-nav">
        <div class="container app-nav-inner">

            @if(Auth::check() && Auth::user()->isAdmin())
                {{-- Admin Nav --}}
                @php $navOutOfStockCount = \App\Models\Product::where('stock', '<=', 0)->count(); @endphp

                <a class="nav-brand" href="{{ route('admin.dashboard') }}">
                    <div class="nav-brand-icon">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                    </div>
                    <span>Razorpay Admin</span>
                </a>

                <div class="nav-links">
                    <a href="{{ route('admin.dashboard') }}"     class="nav-link-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">Dashboard</a>
                    <a href="{{ route('admin.products.index') }}" class="nav-link-item {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">Products</a>
                    <a href="{{ route('admin.coupons.index') }}"  class="nav-link-item {{ request()->routeIs('admin.coupons.*') ? 'active' : '' }}">Coupons</a>
                    <a href="{{ route('admin.orders.index') }}"  class="nav-link-item {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">Orders</a>

                    @if($navOutOfStockCount > 0)
                        <a href="{{ route('admin.dashboard') }}" class="nav-link-item" style="color:#ef4444; background:#fef2f2;" title="Out of stock products">
                            🚨 {{ $navOutOfStockCount }} Out of Stock
                        </a>
                    @endif
                </div>

                <div class="d-flex align-items-center gap-2">
                    <span class="nav-user-chip">
                        👤 {{ Auth::user()->name }}
                        <span style="background:#6f42c1;color:#fff;font-size:0.65rem;padding:0.15rem 0.45rem;border-radius:4px;font-weight:700;">ADMIN</span>
                    </span>
                    <form action="{{ route('logout') }}" method="POST" class="m-0 p-0">
                        @csrf
                        <button type="submit" class="nav-logout-btn">Logout</button>
                    </form>
                </div>

            @else
                {{-- Customer Nav --}}
                <a class="nav-brand" href="{{ route('home') }}">
                    <div class="nav-brand-icon">
                        <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.2"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
                    </div>
                    <span>Razorpay Store</span>
                </a>

                <div class="nav-links">
                    <a href="{{ route('home') }}"          class="nav-link-item {{ request()->routeIs('home') ? 'active' : '' }}">Home</a>
                    <a href="{{ route('products.index') }}" class="nav-link-item {{ request()->routeIs('products.index') ? 'active' : '' }}">Products</a>
                </div>

                <div class="d-flex align-items-center gap-2">
                    @php $cartCount = array_sum(array_column(session('cart', []), 'quantity')); @endphp
                    <a href="{{ route('cart.index') }}" class="nav-cart-btn">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
                        Cart
                        @if($cartCount > 0)
                            <span class="nav-cart-dot">{{ $cartCount }}</span>
                        @endif
                    </a>

                    @auth
                        <span class="nav-user-chip">👤 {{ Auth::user()->name }}</span>
                        <form action="{{ route('logout') }}" method="POST" class="m-0 p-0">
                            @csrf
                            <button type="submit" class="nav-logout-btn">Logout</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="nav-login-btn">Login</a>
                    @endauth
                </div>
            @endif

        </div>
    </nav>
    @endunless

    {{-- ── Main ────────────────────────────────────────── --}}
    <main class="main-content container">

        @if(session('success'))
            <div class="alert alert-success mb-4" role="alert">
                <strong>✓</strong> {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger mb-4" role="alert">
                <strong>✕</strong> {{ session('error') }}
            </div>
        @endif

        @if(session('warning'))
            <div class="alert mb-4" role="alert" style="background: rgba(245, 158, 11, 0.15) !important; color: #fcd34d !important; border-left: 4px solid #f59e0b !important;">
                <strong>⚠️</strong> {{ session('warning') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger mb-4" role="alert">
                <ul class="mb-0 ps-3">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </main>

    {{-- ── Footer ───────────────────────────────────────── --}}
    <footer class="app-footer">
        <div class="container">
            © {{ date('Y') }} Razorpay Demo Store &nbsp;·&nbsp; Secure Payments by Razorpay
        </div>
    </footer>

    {{-- Password toggle script --}}
    <script>
        function togglePasswordVisibility(inputId, btn) {
            const input = document.getElementById(inputId);
            if (!input) return;
            const isPassword = input.type === 'password';
            input.type = isPassword ? 'text' : 'password';
            btn.innerHTML = isPassword
                ? `<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line></svg>`
                : `<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>`;
            btn.setAttribute('title', isPassword ? 'Hide password' : 'Show password');
        }
    </script>
</body>
</html>
