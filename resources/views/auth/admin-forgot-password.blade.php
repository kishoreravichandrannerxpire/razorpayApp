@extends('layouts.app')

@section('title', 'Admin — Forgot Password')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">

        <div class="card auth-card shadow-sm p-4 p-md-5" style="border-top: 5px solid #6f42c1 !important;">
            <div class="text-center mb-4">
                <div class="mb-2" style="font-size: 2.2rem;">🔑</div>
                <h3 class="fw-bold mb-1" style="color: #6f42c1;">Admin Password Reset</h3>
                <p class="text-muted small">Enter the admin email address to receive a 6-digit OTP reset code</p>
            </div>

            {{-- Notice --}}
            <div class="alert alert-secondary py-2 px-3 small mb-4 d-flex align-items-center gap-2">
                <span>🔒</span>
                <span>This reset form is restricted to store administrators only.</span>
            </div>

            @if(session('success'))
                <div class="alert alert-success py-2 px-3 small mb-3">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('admin.password.email') }}" method="POST">
                @csrf

                <div class="mb-4">
                    <label for="email" class="form-label fw-semibold">Admin Email Address <span class="text-danger">*</span></label>
                    <input
                        type="email"
                        class="form-control @error('email') is-invalid @enderror"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                        placeholder="admin@example.com"
                        required
                        autofocus
                    >
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn w-100 py-2 fw-bold shadow-sm mb-3 text-white" style="background-color: #6f42c1; border-color: #6f42c1;">
                    ✉️ Send OTP to Admin Email
                </button>
            </form>

            <div class="text-center pt-3 border-top">
                <a href="{{ route('admin.login') }}" class="text-muted small fw-semibold text-decoration-none" style="color: #6f42c1 !important;">
                    ← Back to Admin Login
                </a>
            </div>
        </div>

    </div>
</div>
@endsection
