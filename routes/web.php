<?php

use App\Http\Controllers\Admin\AdminCouponController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminOrderController;
use App\Http\Controllers\Admin\AdminProductController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\RazorpayWebhookController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

// ── Root: Guests → Login | Customers → Home | Admins → Admin Dashboard ──
Route::get('/', function () {
    if (auth()->check()) {
        if (auth()->user()->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('home');
    }
    return redirect()->route('login');
});

// Admin root redirect
Route::get('/admin', function () {
    return redirect()->route('admin.dashboard');
});

// ── Authentication Routes (guests only) ─────────────────────────────────
Route::get('/login',    [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login',   [AuthController::class, 'login'])->middleware('throttle:5,30')->name('login.submit');
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register',[AuthController::class, 'register'])->name('register.submit');
Route::post('/logout',  [AuthController::class, 'logout'])->name('logout');

// Admin Authentication Routes
Route::get('/admin/login',  [AuthController::class, 'showAdminLoginForm'])->name('admin.login');
Route::post('/admin/login', [AuthController::class, 'adminLogin'])->name('admin.login.submit');

// Customer Password Reset & OTP Routes
Route::get('/forgot-password',  [AuthController::class, 'showForgotPasswordForm'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'sendOtp'])->middleware('throttle:5,30')->name('password.email');
Route::get('/reset-password',   [AuthController::class, 'showResetPasswordForm'])->name('password.reset');
Route::post('/reset-password',  [AuthController::class, 'resetPassword'])->name('password.update');
Route::post('/resend-otp',      [AuthController::class, 'resendOtp'])->middleware('throttle:5,30')->name('password.resend');

// Admin Password Reset & OTP Routes
Route::get('/admin/forgot-password',  [AuthController::class, 'showAdminForgotPasswordForm'])->name('admin.password.request');
Route::post('/admin/forgot-password', [AuthController::class, 'sendAdminOtp'])->name('admin.password.email');
Route::get('/admin/reset-password',   [AuthController::class, 'showAdminResetPasswordForm'])->name('admin.password.reset');
Route::post('/admin/reset-password',  [AuthController::class, 'resetAdminPassword'])->name('admin.password.update');
Route::post('/admin/resend-otp',      [AuthController::class, 'resendAdminOtp'])->name('admin.password.resend');

// ── Authenticated Customer Routes (requires login + not admin) ───────────
Route::middleware(['auth', 'redirect_if_admin'])->group(function () {

    // Home Page
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    // Products Catalog
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');

    // Shopping Cart
    Route::get('/cart',                  [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add',             [CartController::class, 'add'])->name('cart.add');
    Route::post('/cart/update/{id}',     [CartController::class, 'update'])->name('cart.update');
    Route::post('/cart/remove/{id}',     [CartController::class, 'remove'])->name('cart.remove');
    Route::post('/cart/clear',           [CartController::class, 'clear'])->name('cart.clear');
    Route::post('/cart/coupon/apply',    [CartController::class, 'applyCoupon'])->name('cart.coupon.apply');
    Route::post('/cart/coupon/remove',   [CartController::class, 'removeCoupon'])->name('cart.coupon.remove');

    // Razorpay Payment Routes
    Route::post('/payment/create',  [PaymentController::class, 'createOrder'])->name('payment.create');
    Route::post('/payment/verify',  [PaymentController::class, 'verifyPayment'])->name('payment.verify');
    Route::get('/payment/success',  [PaymentController::class, 'success'])->name('payment.success');
    Route::get('/payment/failed',   [PaymentController::class, 'failed'])->name('payment.failed');
});

// Razorpay Webhook (public — Razorpay server calls this)
Route::post('/razorpay/webhook', [RazorpayWebhookController::class, 'handle'])->name('razorpay.webhook');

// ── Admin Routes (Protected: admin middleware) ────────────────────────────
Route::middleware(['admin'])->prefix('admin')->name('admin.')->group(function () {

    // Dashboard
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Product CRUD
    Route::get('/products',               [AdminProductController::class, 'index'])->name('products.index');
    Route::get('/products/create',        [AdminProductController::class, 'create'])->name('products.create');
    Route::post('/products',              [AdminProductController::class, 'store'])->name('products.store');
    Route::get('/products/{product}/edit',[AdminProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{product}',     [AdminProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}',  [AdminProductController::class, 'destroy'])->name('products.destroy');

    // Order Management
    Route::get('/orders',              [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}/edit', [AdminOrderController::class, 'edit'])->name('orders.edit');
    Route::put('/orders/{order}',      [AdminOrderController::class, 'update'])->name('orders.update');

    // Coupon Management
    Route::get('/coupons',               [AdminCouponController::class, 'index'])->name('coupons.index');
    Route::get('/coupons/create',        [AdminCouponController::class, 'create'])->name('coupons.create');
    Route::post('/coupons',              [AdminCouponController::class, 'store'])->name('coupons.store');
    Route::get('/coupons/{coupon}/edit', [AdminCouponController::class, 'edit'])->name('coupons.edit');
    Route::put('/coupons/{coupon}',      [AdminCouponController::class, 'update'])->name('coupons.update');
    Route::delete('/coupons/{coupon}',   [AdminCouponController::class, 'destroy'])->name('coupons.destroy');
    Route::post('/coupons/{coupon}/toggle', [AdminCouponController::class, 'toggle'])->name('coupons.toggle');
});
