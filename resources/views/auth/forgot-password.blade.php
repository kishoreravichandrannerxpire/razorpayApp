@extends('layouts.app')

@section('title', 'Forgot Password — Razorpay Store')

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
                <div class="mb-2" style="font-size: 2.2rem;">🔑</div>
                <h3 class="fw-bold mb-1">Forgot Password</h3>
                <p class="text-muted small">Enter your email and we'll send a 6-digit OTP code to reset your password</p>
            </div>

            <form action="{{ route('password.email') }}" method="POST">
                @csrf

                <!-- Email Input -->
                <div class="mb-4">
                    <label for="email" class="form-label fw-semibold">Registered Email Address <span class="text-danger">*</span></label>
                    <input 
                        type="email" 
                        class="form-control @error('email') is-invalid @enderror" 
                        id="email" 
                        name="email" 
                        value="{{ old('email') }}" 
                        placeholder="name@example.com" 
                        required 
                        autofocus
                    >
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn btn-primary w-100 py-2 fw-bold shadow-sm mb-3">
                    ✉️ Send 6-Digit OTP Code
                </button>
            </form>

            <div class="text-center pt-3 border-top">
                <a href="{{ route('login') }}" class="text-primary small fw-semibold text-decoration-none">
                    ← Back to Customer Login
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
