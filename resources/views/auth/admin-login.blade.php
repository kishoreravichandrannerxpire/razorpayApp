@extends('layouts.app')

@section('title', 'Admin Portal Login — Razorpay Store')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
        <div class="card auth-card shadow-sm p-4 p-md-5" style="border-top: 5px solid #6f42c1 !important;">
            <div class="text-center mb-4">
                <div class="mb-2" style="font-size: 2.5rem;">🛡️</div>
                <h3 class="fw-bold mb-1" style="color: #6f42c1;">Admin Portal</h3>
                <p class="text-muted small">Sign in to manage store products, orders, and inventory</p>
            </div>

            <!-- Notice -->
            <div class="alert alert-secondary py-2 px-3 small mb-4 d-flex align-items-center gap-2">
                <span>🔒</span>
                <span>Restricted to authorized store administrators only.</span>
            </div>

            <form action="{{ route('admin.login.submit') }}" method="POST">
                @csrf

                {{-- Top-level credential error --}}
                @if($errors->has('email'))
                    <div class="alert alert-danger py-2 px-3 small mb-3 d-flex align-items-center gap-2">
                        <span>❌</span>
                        <span>{{ $errors->first('email') }}</span>
                    </div>
                @endif

                <!-- Email Input -->
                <div class="mb-3">
                    <label for="admin_email" class="form-label fw-semibold">Admin Email <span class="text-danger">*</span></label>
                    <input 
                        type="email" 
                        class="form-control @error('email') is-invalid @enderror" 
                        id="admin_email" 
                        name="email" 
                        value="{{ old('email') }}" 
                        placeholder="admin@example.com" 
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
                        <label for="admin_password" class="form-label fw-semibold mb-0">Admin Password <span class="text-danger">*</span></label>
                    </div>
                    <div class="input-group">
                        <input 
                            type="password" 
                            class="form-control @error('password') is-invalid @enderror" 
                            id="admin_password" 
                            name="password" 
                            placeholder="Enter your admin password" 
                            required 
                            autocomplete="current-password"
                        >
                        <button class="btn btn-outline-secondary d-flex align-items-center px-3" type="button" onclick="togglePasswordVisibility('admin_password', this)" title="Show password">
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
                    <input type="checkbox" class="form-check-input" id="admin_remember" name="remember" value="1">
                    <label class="form-check-label text-secondary small" for="admin_remember">Remember this admin session</label>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn w-100 mb-3 fw-bold text-white shadow-sm" style="background-color: #6f42c1; border-color: #6f42c1;">
                    🛡️ Sign In as Admin
                </button>
            </form>

            {{-- ── Forgot Password ────────────────────────────────────────────── --}}
            <div class="text-center pt-2">
                <a href="{{ route('admin.password.request') }}" class="text-muted small text-decoration-none d-inline-flex align-items-center justify-content-center gap-1">
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
