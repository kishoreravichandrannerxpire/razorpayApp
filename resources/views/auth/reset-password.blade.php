@extends('layouts.app')

@section('title', 'Verify OTP & Reset Password — Razorpay Store')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
        <!-- Store Brand Header -->
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
                <div class="mb-2" style="font-size: 2.2rem;">🔐</div>
                <h3 class="fw-bold mb-1">Enter OTP & New Password</h3>
                <p class="text-muted small">Enter the 6-digit code sent to your email to set a new password</p>
            </div>

           @if(session('success'))
    <div class="alert alert-success py-2 px-3 small mb-3">{{ session('success') }}</div>
@endif

            <form action="{{ route('password.update') }}" method="POST">
                @csrf

                <!-- Email Input -->
                <div class="mb-3">
                    <label for="email" class="form-label fw-semibold">Email Address</label>
                    <input 
                        type="email" 
                        class="form-control bg-light @error('email') is-invalid @enderror" 
                        id="email" 
                        name="email" 
                        value="{{ old('email', $email ?? '') }}" 
                        required 
                        readonly
                    >
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- 6-digit OTP Code Input -->
                <div class="mb-3">
                    <label for="otp" class="form-label fw-semibold">6-Digit OTP Code <span class="text-danger">*</span></label>
                    <input 
                        type="text" 
                        class="form-control text-center fw-bold fs-5 tracking-wide @error('otp') is-invalid @enderror" 
                        id="otp" 
                        name="otp" 
                        value="{{ old('otp') }}" 
                        placeholder="••••••" 
                        maxlength="6"
                        pattern="\d{6}"
                        required 
                        autofocus
                        style="letter-spacing: 0.3em;"
                    >
                    @error('otp')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- New Password Input with Eye Toggle -->
                <div class="mb-3">
                    <label for="reset_password" class="form-label fw-semibold">New Password <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <input 
                            type="password" 
                            class="form-control @error('password') is-invalid @enderror" 
                            id="reset_password" 
                            name="password" 
                            placeholder="Minimum 6 characters" 
                            required 
                            autocomplete="new-password"
                        >
                        <button class="btn btn-outline-secondary d-flex align-items-center px-3" type="button" onclick="togglePasswordVisibility('reset_password', this)" title="Show password">
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

                <!-- Confirm New Password Input with Eye Toggle -->
                <div class="mb-4">
                    <label for="reset_password_confirmation" class="form-label fw-semibold">Confirm New Password <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <input 
                            type="password" 
                            class="form-control" 
                            id="reset_password_confirmation" 
                            name="password_confirmation" 
                            placeholder="Re-enter new password" 
                            required 
                            autocomplete="new-password"
                        >
                        <button class="btn btn-outline-secondary d-flex align-items-center px-3" type="button" onclick="togglePasswordVisibility('reset_password_confirmation', this)" title="Show password">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                <circle cx="12" cy="12" r="3"></circle>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn btn-primary w-100 py-2 fw-bold shadow-sm mb-3">
                    ✓ Reset & Save New Password
                </button>
            </form>

            <!-- Resend OTP Form -->
            <form action="{{ route('password.resend') }}" method="POST" class="text-center mb-3">
                @csrf
                <input type="hidden" name="email" value="{{ old('email', $email ?? '') }}">
                <span class="text-muted small">Didn't receive the OTP? </span>
                <button type="submit" class="btn btn-link btn-sm p-0 text-decoration-none fw-semibold">
                    Resend OTP
                </button>
            </form>

            <div class="text-center pt-3 border-top">
                <a href="{{ route('login') }}" class="text-muted small text-decoration-none">
                    ← Back to Customer Login
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
