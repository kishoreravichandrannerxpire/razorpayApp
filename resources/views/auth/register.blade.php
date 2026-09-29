@extends('layouts.app')

@section('title', 'Register - Razorpay Store')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
        <div class="card auth-card shadow-sm p-4 p-md-5">
            <div class="text-center mb-4">
                <h3 class="fw-bold mb-1">Create Account</h3>
                <p class="text-muted">Register and start ordering</p>
            </div>

            <form action="{{ route('register.submit') }}" method="POST">
                @csrf

                <!-- Name Input -->
                <div class="mb-3">
                    <label for="name" class="form-label fw-semibold">Full Name <span class="text-danger">*</span></label>
                    <input 
                        type="text" 
                        class="form-control @error('name') is-invalid @enderror" 
                        id="name" 
                        name="name" 
                        value="{{ old('name') }}" 
                        placeholder="e.g. Kishore Kumar" 
                        required 
                        autocomplete="name"
                    >
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

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

                <!-- Phone Input (Optional) -->
                <div class="mb-3">
                    <label for="phone" class="form-label fw-semibold">Phone Number (Optional)</label>
                    <input 
                        type="tel" 
                        class="form-control @error('phone') is-invalid @enderror" 
                        id="phone" 
                        name="phone" 
                        value="{{ old('phone') }}" 
                        placeholder="e.g. 9876543210" 
                        autocomplete="tel"
                    >
                    @error('phone')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Password Input -->
                <div class="mb-3">
                    <label for="password" class="form-label fw-semibold">Password <span class="text-danger">*</span></label>
                    <input 
                        type="password" 
                        class="form-control @error('password') is-invalid @enderror" 
                        id="password" 
                        name="password" 
                        placeholder="Minimum 6 characters" 
                        required 
                        autocomplete="new-password"
                    >
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Password Confirmation Input -->
                <div class="mb-4">
                    <label for="password_confirmation" class="form-label fw-semibold">Confirm Password <span class="text-danger">*</span></label>
                    <input 
                        type="password" 
                        class="form-control" 
                        id="password_confirmation" 
                        name="password_confirmation" 
                        placeholder="Re-enter password" 
                        required 
                        autocomplete="new-password"
                    >
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn btn-primary w-100 mb-3">
                    Register Account
                </button>
            </form>

            <div class="text-center pt-2 border-top">
                <p class="text-muted small mb-0">
                    Already have an account? <a href="{{ route('login') }}" class="text-primary fw-semibold text-decoration-none">Sign In</a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
