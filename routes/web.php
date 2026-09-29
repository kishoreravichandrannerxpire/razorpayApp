<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminOrderController;
use App\Http\Controllers\Admin\AdminProductController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\RazorpayWebhookController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

// Home Redirect
Route::get('/', function () {
    if (auth()->check() && auth()->user()->isAdmin()) {
        return redirect()->route('admin.dashboard');
    }
    return redirect()->route('products.index');
});

// Authentication Routes (Pure HTML forms - No JS)
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Admin Authentication Routes
Route::get('/admin/login', [AuthController::class, 'showAdminLoginForm'])->name('admin.login');
Route::post('/admin/login', [AuthController::class, 'adminLogin'])->name('admin.login.submit');

// Customer Password Reset & OTP Routes
Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'sendOtp'])->name('password.email');
Route::get('/reset-password', [AuthController::class, 'showResetPasswordForm'])->name('password.reset');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');
Route::post('/resend-otp', [AuthController::class, 'resendOtp'])->name('password.resend');

// Admin Password Reset & OTP Routes (Separate — admin portal only)
Route::get('/admin/forgot-password', [AuthController::class, 'showAdminForgotPasswordForm'])->name('admin.password.request');
Route::post('/admin/forgot-password', [AuthController::class, 'sendAdminOtp'])->name('admin.password.email');
Route::get('/admin/reset-password', [AuthController::class, 'showAdminResetPasswordForm'])->name('admin.password.reset');
Route::post('/admin/reset-password', [AuthController::class, 'resetAdminPassword'])->name('admin.password.update');
Route::post('/admin/resend-otp', [AuthController::class, 'resendAdminOtp'])->name('admin.password.resend');

// Products Catalog (Customers only)
Route::get('/products', [ProductController::class, 'index'])
    ->name('products.index')
    ->middleware('redirect_if_admin');

// Shopping Cart Routes (Customers only)
Route::middleware('redirect_if_admin')->group(function () {
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::post('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');
    Route::post('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
    Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');
});

// Razorpay Payment Routes (Protected: Authenticated non-admin users only)
Route::middleware(['auth', 'redirect_if_admin'])->group(function () {
    Route::post('/payment/create', [PaymentController::class, 'createOrder'])->name('payment.create');
    Route::post('/payment/verify', [PaymentController::class, 'verifyPayment'])->name('payment.verify');
    Route::get('/payment/success', [PaymentController::class, 'success'])->name('payment.success');
    Route::get('/payment/failed', [PaymentController::class, 'failed'])->name('payment.failed');
});

// Razorpay Webhook Route
Route::post('/razorpay/webhook', [RazorpayWebhookController::class, 'handle'])->name('razorpay.webhook');

// ─── Admin Routes (Protected: admin middleware) ────────────────────────
Route::middleware(['admin'])->prefix('admin')->name('admin.')->group(function () {

    // Dashboard
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Product CRUD
    Route::get('/products',              [AdminProductController::class, 'index'])->name('products.index');
    Route::get('/products/create',       [AdminProductController::class, 'create'])->name('products.create');
    Route::post('/products',             [AdminProductController::class, 'store'])->name('products.store');
    Route::get('/products/{product}/edit',    [AdminProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{product}',         [AdminProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}',      [AdminProductController::class, 'destroy'])->name('products.destroy');

    // Order Management
    Route::get('/orders',                    [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}/edit',       [AdminOrderController::class, 'edit'])->name('orders.edit');
    Route::put('/orders/{order}',            [AdminOrderController::class, 'update'])->name('orders.update');
});

