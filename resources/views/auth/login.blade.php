@extends('layouts.app')

@section('title', 'Login - Razorpay Store')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
        <!-- Store Brand Header above Login Card (Since navbar & cart are hidden) -->
        <div class="text-center mb-4">
            <a href="{{ route('products.index') }}" class="d-inline-flex align-items-center gap-2 text-decoration-none fs-3 fw-bold text-primary">
                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-primary">
                    <circle cx="9" cy="21" r="1"></circle>
                    <circle cx="20" cy="21" r="1"></circle>
                    <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                </svg>
                <span>Razorpay Store</span>
            </a>
        </div>

        <div class="card auth-card shadow-sm p-4 p-md-5">
            <div class="text-center mb-4">
                <div class="mb-2">
                    <span style="font-size: 2.2rem;">👋</span>
                </div>
                <h3 class="fw-bold mb-1">Welcome Back</h3>
                <p class="text-muted small">Sign in to your account to continue shopping</p>
            </div>

            <form action="{{ route('login.submit') }}" method="POST">
                @csrf

                <!-- Email Input -->
                <div class="mb-3">
                    <label for="email" class="form-label fw-semibold">Email Address <span class="text-danger">*</span></label>
                    <input 
                        type="email" 
                        class="form-control @error('email') is-invalid @enderror" 
                        id="email" 
                        name="email" 
                        value="{{ old('email') }}" 
                        placeholder="name@example.com" 
                        required 
                        autocomplete="email"
                    >
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Password Input with Eye Toggle -->
                <div class="mb-3">
                    <div class="mb-1">
                        <label for="password" class="form-label fw-semibold mb-0">Password <span class="text-danger">*</span></label>
                    </div>
                    <div class="input-group">
                        <input 
                            type="password" 
                            class="form-control @error('password') is-invalid @enderror" 
                            id="password" 
                            name="password" 
                            placeholder="Enter your password" 
                            required 
                            autocomplete="current-password"
                        >
                        <button class="btn btn-outline-secondary d-flex align-items-center px-3" type="button" onclick="togglePasswordVisibility('password', this)" title="Show password">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                <circle cx="12" cy="12" r="3"></circle>
                            </svg>
                        </button>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Remember Me Checkbox -->
                <div class="mb-4 form-check">
                    <input type="checkbox" class="form-check-input" id="remember" name="remember" value="1">
                    <label class="form-check-label text-secondary small" for="remember">Keep me signed in</label>
                </div>

                <!-- Login Submit Button -->
                <button type="submit" class="btn btn-primary w-100 py-2 fw-bold shadow-sm mb-3">
                    Sign In / Login
                </button>
            </form>

            {{-- ── Register Section directly below Login Button ──────────────────────── --}}
            <div class="text-center my-3 position-relative">
                <hr class="text-muted">
                <span class="position-absolute top-50 start-50 translate-middle bg-white px-3 text-muted small fw-semibold">
                    New Customer?
                </span>
            </div>

            <div class="d-grid mb-3">
                <a href="{{ route('register') }}" class="btn btn-outline-secondary py-2 fw-semibold d-flex align-items-center justify-content-center gap-2">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                        <circle cx="8.5" cy="7" r="4"></circle>
                        <line x1="20" y1="8" x2="20" y2="14"></line>
                        <line x1="23" y1="11" x2="17" y2="11"></line>
                    </svg>
                    <span>Create New Account / Register</span>
                </a>
            </div>

            {{-- ── Forgot Password ───────────────────────────────────────────────── --}}
            <div class="text-center pt-2">
                <a href="{{ route('password.request') }}" class="text-muted small text-decoration-none d-inline-flex align-items-center justify-content-center gap-1">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                        <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                    </svg>
                    Forgot Password?
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
