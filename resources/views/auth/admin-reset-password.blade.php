@extends('layouts.app')

@section('title', 'Admin — Verify OTP & Reset Password')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">

        <div class="card auth-card shadow-sm p-4 p-md-5" style="border-top: 5px solid #6f42c1 !important;">
            <div class="text-center mb-4">
                <div class="mb-2" style="font-size: 2.2rem;">🔐</div>
                <h3 class="fw-bold mb-1" style="color: #6f42c1;">Admin — Enter OTP & New Password</h3>
                <p class="text-muted small">Enter the 6-digit code sent to your admin email to set a new password</p>
            </div>

            @if(session('success'))
                <div class="alert alert-success py-2 px-3 small mb-3">{{ session('success') }}</div>
            @endif

            <form action="{{ route('admin.password.update') }}" method="POST">
                @csrf

                <!-- Email (read-only) -->
                <div class="mb-3">
                    <label for="email" class="form-label fw-semibold">Admin Email Address</label>
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

                <!-- 6-digit OTP -->
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

                <!-- New Password -->
                <div class="mb-3">
                    <label for="admin_reset_password" class="form-label fw-semibold">New Password <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <input
                            type="password"
                            class="form-control @error('password') is-invalid @enderror"
                            id="admin_reset_password"
                            name="password"
                            placeholder="Minimum 6 characters"
                            required
                            autocomplete="new-password"
                        >
                        <button class="btn btn-outline-secondary d-flex align-items-center px-3" type="button" onclick="togglePasswordVisibility('admin_reset_password', this)" title="Show password">
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

                <!-- Confirm New Password -->
                <div class="mb-4">
                    <label for="admin_reset_password_confirmation" class="form-label fw-semibold">Confirm New Password <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <input
                            type="password"
                            class="form-control"
                            id="admin_reset_password_confirmation"
                            name="password_confirmation"
                            placeholder="Re-enter new password"
                            required
                            autocomplete="new-password"
                        >
                        <button class="btn btn-outline-secondary d-flex align-items-center px-3" type="button" onclick="togglePasswordVisibility('admin_reset_password_confirmation', this)" title="Show password">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                <circle cx="12" cy="12" r="3"></circle>
                            </svg>
                        </button>
                    </div>
                </div>

                <button type="submit" class="btn w-100 py-2 fw-bold shadow-sm mb-3 text-white" style="background-color: #6f42c1; border-color: #6f42c1;">
                    ✓ Reset Admin Password
                </button>
            </form>

            <!-- Resend OTP Form -->
            <form action="{{ route('admin.password.resend') }}" method="POST" class="text-center mb-3">
                @csrf
                <input type="hidden" name="email" value="{{ old('email', $email ?? '') }}">
                <span class="text-muted small">Didn't receive the OTP? </span>
                <button type="submit" class="btn btn-link btn-sm p-0 text-decoration-none fw-semibold" style="color: #6f42c1;">
                    Resend OTP
                </button>
            </form>

            <div class="text-center pt-3 border-top">
                <a href="{{ route('admin.login') }}" class="text-muted small text-decoration-none">
                    ← Back to Admin Login
                </a>
            </div>
        </div>

    </div>
</div>
@endsection
