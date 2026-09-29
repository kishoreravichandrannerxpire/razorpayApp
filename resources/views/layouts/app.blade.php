<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Razorpay Store')</title>

    <!-- Bootstrap 5 CSS (Strictly CSS only - No JavaScript) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS styling for aesthetic look and feel -->
    <style>
        body {
            background-color: #f4f6f9;
            font-family: system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            color: #333;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .navbar-brand {
            font-weight: 700;
            letter-spacing: -0.5px;
            color: #0d6efd !important;
        }

        .navbar {
            background-color: #ffffff;
            box-shadow: 0 2px 10px rgba(0,0,0,0.06);
        }

        .main-content {
            flex: 1;
            padding-top: 2rem;
            padding-bottom: 3rem;
        }

        .card {
            border: 1px solid #e9ecef;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .product-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
        }

        .auth-card {
            max-width: 480px;
            margin: 0 auto;
            border-radius: 16px;
        }

        .btn-primary {
            background-color: #2b6cb0;
            border-color: #2b6cb0;
            font-weight: 600;
            border-radius: 8px;
            padding: 0.6rem 1.25rem;
        }

        .btn-primary:hover {
            background-color: #1a4971;
            border-color: #1a4971;
        }

        .form-control {
            border-radius: 8px;
            padding: 0.65rem 0.9rem;
            border: 1px solid #cbd5e0;
        }

        .form-control:focus {
            border-color: #2b6cb0;
            box-shadow: 0 0 0 0.25rem rgba(43, 108, 176, 0.25);
        }

        .badge-stock {
            font-size: 0.8rem;
            padding: 0.35em 0.7em;
            border-radius: 6px;
        }

        .price-tag {
            font-size: 1.4rem;
            font-weight: 700;
            color: #198754;
        }

        footer {
            background-color: #ffffff;
            border-top: 1px solid #e9ecef;
            padding: 1.5rem 0;
            color: #6c757d;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>

    <!-- Navigation Header (Hidden on Login, Register, and Password Reset pages) -->
    @unless(request()->routeIs('login', 'register', 'admin.login', 'password.request', 'password.reset', 'admin.password.request', 'admin.password.reset'))
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container">
            @if(Auth::check() && Auth::user()->isAdmin())
                {{-- Admin Navigation Bar --}}
                @php
                    $navOutOfStockCount = \App\Models\Product::where('stock', '<=', 0)->count();
                @endphp
                <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('admin.dashboard') }}">
                    <span style="font-size: 1.4rem;">🛡️</span>
                    <span style="color:#6f42c1;">Razorpay Admin</span>
                </a>

                <div class="d-flex align-items-center gap-3">
                    <a href="{{ route('admin.dashboard') }}" class="nav-link fw-semibold text-secondary">Dashboard</a>
                    <a href="{{ route('admin.products.index') }}" class="nav-link fw-semibold text-secondary">Manage Products</a>
                    <a href="{{ route('admin.orders.index') }}" class="nav-link fw-semibold text-secondary">Manage Orders</a>

                    @if($navOutOfStockCount > 0)
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-sm btn-outline-danger fw-bold d-flex align-items-center gap-1 shadow-sm px-2 py-1" title="Products needing restock">
                            <span>🚨</span>
                            <span>{{ $navOutOfStockCount }} Out of Stock</span>
                        </a>
                    @endif

                    <span class="badge bg-light text-dark border px-3 py-2 ms-2">
                        👤 {{ Auth::user()->name }}
                        <span class="badge ms-1" style="background-color:#6f42c1; color:white; font-size:0.68rem;">ADMIN</span>
                    </span>

                    <form action="{{ route('logout') }}" method="POST" class="d-inline m-0 p-0">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger btn-sm fw-semibold">
                            Logout
                        </button>
                    </form>
                </div>
            @else
                {{-- Customer / Guest Navigation Bar --}}
                <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('products.index') }}">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-primary">
                        <circle cx="9" cy="21" r="1"></circle>
                        <circle cx="20" cy="21" r="1"></circle>
                        <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                    </svg>
                    <span>Razorpay Store</span>
                </a>

                <div class="d-flex align-items-center gap-3">
                    <a href="{{ route('products.index') }}" class="nav-link fw-semibold">Products</a>

                    <a href="{{ route('cart.index') }}" class="btn btn-outline-primary btn-sm d-flex align-items-center gap-2 position-relative">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="9" cy="21" r="1"></circle>
                            <circle cx="20" cy="21" r="1"></circle>
                            <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                        </svg>
                        <span>Cart</span>
                        @php
                            $cartCount = array_sum(array_column(session('cart', []), 'quantity'));
                        @endphp
                        @if($cartCount > 0)
                            <span class="badge bg-danger rounded-pill px-2 py-1" style="font-size: 0.72rem;">
                                {{ $cartCount }}
                            </span>
                        @endif
                    </a>

                    @auth
                        <span class="badge bg-light text-dark border px-3 py-2">
                            👤 {{ Auth::user()->name }}
                        </span>

                        <form action="{{ route('logout') }}" method="POST" class="d-inline m-0 p-0">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger btn-sm fw-semibold">
                                Logout
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-primary btn-sm">Login</a>
                        <a href="{{ route('register') }}" class="btn btn-outline-secondary btn-sm">Register</a>
                    @endauth
                </div>
            @endif
        </div>
    </nav>
    @endunless

    <!-- Main Container -->
    <main class="main-content container">
        <!-- Flash Messages -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm mb-4" role="alert">
                <strong>✓ Success:</strong> {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger shadow-sm mb-4" role="alert">
                <strong>✕ Error:</strong> {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger shadow-sm mb-4" role="alert">
                <ul class="mb-0 ps-3">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="text-center">
        <div class="container">
            <p class="mb-0">© {{ date('Y') }} Razorpay Demo Store. Pure HTML & Bootstrap CSS (No JavaScript).</p>
        </div>
    </footer>

    <!-- Password Visibility Toggle Script -->
    <script>
        function togglePasswordVisibility(inputId, btn) {
            const input = document.getElementById(inputId);
            if (!input) return;
            const isPassword = input.type === 'password';
            input.type = isPassword ? 'text' : 'password';

            if (isPassword) {
                // Eye Slash icon (Visible state)
                btn.innerHTML = `<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line></svg>`;
                btn.setAttribute('title', 'Hide password');
            } else {
                // Eye icon (Hidden state)
                btn.innerHTML = `<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>`;
                btn.setAttribute('title', 'Show password');
            }
        }
    </script>
</body>
</html>
