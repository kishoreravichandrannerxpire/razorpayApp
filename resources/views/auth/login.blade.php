@extends('layouts.app')

@section('title', 'Sign In — Razorpay Store')

@section('content')

<div class="lp-wrap">
    {{-- Left Panel: Brand/Visual --}}
    <div class="lp-left" aria-hidden="true">
        <div class="lp-left-inner">
            <div class="lp-brand">
                <div class="lp-brand-icon">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.2"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
                </div>
                <span>Razorpay Store</span>
            </div>
            <h2 class="lp-tagline">Shop smarter.<br><span class="lp-tagline-accent">Pay instantly.</span></h2>
            <p class="lp-desc">Discover premium tech products and check out in seconds with any payment method.</p>

            <div class="lp-feat-list">
                <div class="lp-feat">
                    <div class="lp-feat-icon">🔐</div>
                    <div>
                        <div class="lp-feat-title">Bank-grade Security</div>
                        <div class="lp-feat-sub">256-bit SSL on every transaction</div>
                    </div>
                </div>
                <div class="lp-feat">
                    <div class="lp-feat-icon">⚡</div>
                    <div>
                        <div class="lp-feat-title">Instant Checkout</div>
                        <div class="lp-feat-sub">UPI, cards, netbanking & more</div>
                    </div>
                </div>
                <div class="lp-feat">
                    <div class="lp-feat-icon">📦</div>
                    <div>
                        <div class="lp-feat-title">Live Stock Updates</div>
                        <div class="lp-feat-sub">Always accurate, never oversell</div>
                    </div>
                </div>
            </div>

            {{-- Floating product images --}}
            <div class="lp-float-imgs" aria-hidden="true">
                <img src="https://images.unsplash.com/photo-1496181133206-80ce9b88a853?w=220&q=80&fit=crop" alt="" class="lp-img lp-img-1">
                <img src="https://images.unsplash.com/photo-1527864550417-7fd91fc51a46?w=220&q=80&fit=crop" alt="" class="lp-img lp-img-2">
                <img src="https://images.unsplash.com/photo-1601445638532-3c6f6c3aa1d6?w=220&q=80&fit=crop" alt="" class="lp-img lp-img-3">
            </div>
        </div>
    </div>

    {{-- Right Panel: Login Form --}}
    <div class="lp-right">
        <div class="lp-form-wrap">

            {{-- Alerts --}}
            @if(session('success'))
                <div class="lp-alert lp-alert-success">✓ {{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="lp-alert lp-alert-danger">✕ {{ session('error') }}</div>
            @endif

            <div class="lp-form-header">
                <h1 class="lp-form-title">Welcome back 👋</h1>
                <p class="lp-form-sub">Sign in to your account to continue shopping</p>
            </div>

            <form action="{{ route('login.submit') }}" method="POST" class="lp-form" id="login-form">
                @csrf

                {{-- Email --}}
                <div class="lp-field">
                    <label for="email" class="lp-label">Email Address <span class="lp-req">*</span></label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        class="lp-input @error('email') lp-input-error @enderror"
                        value="{{ old('email') }}"
                        placeholder="you@example.com"
                        required
                        autocomplete="email"
                    >
                    @error('email')
                        <div class="lp-error-msg">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Password --}}
                <div class="lp-field">
                    <div class="lp-label-row">
                        <label for="password" class="lp-label">Password <span class="lp-req">*</span></label>
                        <a href="{{ route('password.request') }}" class="lp-forgot-link">Forgot password?</a>
                    </div>
                    <div class="lp-input-group">
                        <input
                            type="password"
                            id="password"
                            name="password"
                            class="lp-input @error('password') lp-input-error @enderror"
                            placeholder="Enter your password"
                            required
                            autocomplete="current-password"
                            style="border-radius: 10px 0 0 10px; border-right: none;"
                        >
                        <button type="button" class="lp-eye-btn" onclick="togglePasswordVisibility('password', this)" title="Show password">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                        </button>
                    </div>
                    @error('password')
                        <div class="lp-error-msg">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Remember --}}
                <div class="lp-remember">
                    <input type="checkbox" id="remember" name="remember" value="1" class="lp-checkbox">
                    <label for="remember" class="lp-remember-label">Keep me signed in</label>
                </div>

                {{-- Submit --}}
                <button type="submit" class="lp-submit-btn" id="login-submit-btn">
                    Sign In
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                </button>
            </form>

            <div class="lp-divider"><span>New to Razorpay Store?</span></div>

            <a href="{{ route('register') }}" class="lp-register-btn" id="goto-register-btn">
                Create a Free Account →
            </a>

        </div>
    </div>
</div>

<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap');

/* ── Full screen override for login page ── */
body { background: #fff !important; padding: 0; }
.main-content { padding: 0 !important; }

/* ── Layout ────────────────────────────── */
.lp-wrap {
    display: flex;
    min-height: 100vh;
}

/* ── Left Panel ────────────────────────── */
.lp-left {
    display: none;
    width: 52%;
    background: linear-gradient(145deg, #0a1628 0%, #1a3a6b 55%, #2d1b69 100%);
    position: relative;
    overflow: hidden;
    padding: 3rem;
}
@media (min-width: 900px) { .lp-left { display: flex; align-items: center; } }

.lp-left::before {
    content: '';
    position: absolute;
    inset: 0;
    background-image:
        linear-gradient(rgba(59,130,246,0.06) 1px, transparent 1px),
        linear-gradient(90deg, rgba(59,130,246,0.06) 1px, transparent 1px);
    background-size: 44px 44px;
    animation: gridShift 18s linear infinite;
}
@keyframes gridShift { from { background-position: 0 0; } to { background-position: 44px 44px; } }

.lp-left-inner { position: relative; z-index: 2; width: 100%; }

.lp-brand {
    display: flex;
    align-items: center;
    gap: 0.7rem;
    margin-bottom: 3rem;
}
.lp-brand-icon {
    width: 42px; height: 42px;
    background: linear-gradient(135deg, #3b82f6, #6366f1);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 4px 16px rgba(99,102,241,0.4);
}
.lp-brand span {
    font-size: 1.1rem;
    font-weight: 800;
    color: #fff;
    letter-spacing: -0.3px;
}

.lp-tagline {
    font-size: clamp(1.8rem, 3vw, 2.6rem);
    font-weight: 900;
    color: #fff;
    line-height: 1.2;
    letter-spacing: -1px;
    margin-bottom: 1rem;
}
.lp-tagline-accent {
    background: linear-gradient(90deg, #60a5fa, #a78bfa);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}
.lp-desc {
    color: rgba(255,255,255,0.6);
    font-size: 0.95rem;
    line-height: 1.7;
    margin-bottom: 2.5rem;
}

.lp-feat-list { display: flex; flex-direction: column; gap: 1.2rem; margin-bottom: 2.5rem; }
.lp-feat { display: flex; align-items: center; gap: 1rem; }
.lp-feat-icon { font-size: 1.5rem; line-height: 1; }
.lp-feat-title { font-size: 0.92rem; font-weight: 700; color: #fff; margin-bottom: 0.1rem; }
.lp-feat-sub   { font-size: 0.78rem; color: rgba(255,255,255,0.5); }

/* Floating product images */
.lp-float-imgs { position: relative; height: 120px; }
.lp-img {
    position: absolute;
    border-radius: 14px;
    object-fit: cover;
    box-shadow: 0 10px 32px rgba(0,0,0,0.4);
    border: 2px solid rgba(255,255,255,0.12);
}
.lp-img-1 { width: 130px; height: 90px; left: 0; top: 0; animation: floatA 5s ease-in-out infinite; }
.lp-img-2 { width: 130px; height: 90px; left: 120px; top: 20px; animation: floatB 6s 1s ease-in-out infinite; }
.lp-img-3 { width: 130px; height: 90px; left: 240px; top: 5px; animation: floatA 4.5s 2s ease-in-out infinite; }
@keyframes floatA { 0%,100%{ transform:translateY(0); } 50%{ transform:translateY(-8px); } }
@keyframes floatB { 0%,100%{ transform:translateY(0); } 50%{ transform:translateY(8px); } }

/* ── Right Panel ───────────────────────── */
.lp-right {
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #fff;
    padding: 2.5rem 1.5rem;
}
.lp-form-wrap { width: 100%; max-width: 400px; }

/* Alerts */
.lp-alert {
    border-radius: 10px;
    padding: 0.8rem 1rem;
    font-size: 0.88rem;
    font-weight: 600;
    margin-bottom: 1.2rem;
}
.lp-alert-success { background: #f0fdf4; color: #15803d; border-left: 4px solid #22c55e; }
.lp-alert-danger  { background: #fef2f2; color: #b91c1c; border-left: 4px solid #ef4444; }

/* Header */
.lp-form-header { margin-bottom: 2rem; }
.lp-form-title {
    font-size: 1.9rem;
    font-weight: 900;
    color: #0f172a;
    letter-spacing: -0.8px;
    margin-bottom: 0.35rem;
}
.lp-form-sub { font-size: 0.9rem; color: #64748b; margin: 0; }

/* Fields */
.lp-field { margin-bottom: 1.2rem; }
.lp-label-row { display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.4rem; }
.lp-label { font-size: 0.85rem; font-weight: 700; color: #374151; display: block; margin-bottom: 0.4rem; }
.lp-req { color: #ef4444; }
.lp-forgot-link { font-size: 0.78rem; font-weight: 600; color: #6366f1; text-decoration: none; }
.lp-forgot-link:hover { text-decoration: underline; }

.lp-input {
    width: 100%;
    padding: 0.72rem 1rem;
    font-family: 'Inter', sans-serif;
    font-size: 0.92rem;
    color: #1e293b;
    background: #f8fafc;
    border: 1.5px solid #e2e8f0;
    border-radius: 10px;
    outline: none;
    transition: border-color 0.18s, box-shadow 0.18s, background 0.18s;
}
.lp-input:focus {
    border-color: #6366f1;
    background: #fff;
    box-shadow: 0 0 0 3px rgba(99,102,241,0.12);
}
.lp-input-error { border-color: #ef4444 !important; }
.lp-error-msg { font-size: 0.78rem; color: #ef4444; font-weight: 600; margin-top: 0.3rem; }

/* Input group (password + eye) */
.lp-input-group {
    display: flex;
    align-items: center;
    background: #f8fafc;
    border: 1.5px solid #e2e8f0;
    border-radius: 10px;
    overflow: hidden;
    transition: border-color 0.18s, box-shadow 0.18s;
}
.lp-input-group:focus-within {
    border-color: #6366f1;
    background: #fff;
    box-shadow: 0 0 0 3px rgba(99,102,241,0.12);
}
.lp-input-group .lp-input {
    border: none;
    background: transparent;
    flex: 1;
    box-shadow: none;
    border-radius: 10px 0 0 10px;
}
.lp-input-group .lp-input:focus { border: none; box-shadow: none; background: transparent; }
.lp-eye-btn {
    background: transparent;
    border: none;
    padding: 0 0.85rem;
    color: #94a3b8;
    cursor: pointer;
    display: flex;
    align-items: center;
    transition: color 0.15s;
}
.lp-eye-btn:hover { color: #6366f1; }

/* Remember */
.lp-remember { display: flex; align-items: center; gap: 0.55rem; margin-bottom: 1.5rem; }
.lp-checkbox { width: 16px; height: 16px; accent-color: #6366f1; cursor: pointer; }
.lp-remember-label { font-size: 0.84rem; color: #64748b; font-weight: 500; cursor: pointer; margin: 0; }

/* Submit */
.lp-submit-btn {
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.6rem;
    background: linear-gradient(135deg, #3b82f6, #6366f1);
    color: #fff;
    font-family: 'Inter', sans-serif;
    font-size: 0.97rem;
    font-weight: 800;
    padding: 0.85rem 1.5rem;
    border: none;
    border-radius: 12px;
    cursor: pointer;
    box-shadow: 0 6px 20px rgba(99,102,241,0.38);
    transition: transform 0.2s ease, box-shadow 0.2s ease;
    letter-spacing: 0.1px;
}
.lp-submit-btn:hover { transform: translateY(-2px); box-shadow: 0 10px 28px rgba(99,102,241,0.5); }

/* Divider */
.lp-divider {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    margin: 1.5rem 0 1rem;
}
.lp-divider::before, .lp-divider::after {
    content: ''; flex: 1; height: 1px; background: #e2e8f0;
}
.lp-divider span { font-size: 0.8rem; color: #94a3b8; font-weight: 600; white-space: nowrap; }

/* Register link */
.lp-register-btn {
    display: block;
    width: 100%;
    text-align: center;
    padding: 0.8rem;
    border: 1.5px solid #e2e8f0;
    border-radius: 12px;
    font-size: 0.9rem;
    font-weight: 700;
    color: #374151;
    text-decoration: none;
    background: #f8fafc;
    transition: all 0.2s;
}
.lp-register-btn:hover { border-color: #6366f1; color: #6366f1; background: #eff6ff; }
</style>

@endsection
