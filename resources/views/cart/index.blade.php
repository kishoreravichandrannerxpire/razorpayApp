@extends('layouts.app')

@section('title', 'Shopping Cart - Razorpay Store')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 pb-2 border-bottom">
    <div>
        <h2 class="h3 fw-bold mb-1 text-white">Shopping Cart</h2>
        <p class="text-muted mb-0">Review your selected items and apply promo coupons before checkout</p>
    </div>
    <div>
        <a href="{{ route('products.index') }}" class="btn btn-outline-secondary btn-sm d-flex align-items-center gap-1">
            <span>&larr; Continue Shopping</span>
        </a>
    </div>
</div>

@if(count($cart) > 0)
    <div class="row g-4">
        <!-- Cart Items List (Col 8) -->
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th scope="col" class="ps-4 py-3" style="min-width: 220px;">Product</th>
                                    <th scope="col" class="py-3 text-center">Unit Price</th>
                                    <th scope="col" class="py-3 text-center" style="width: 160px;">Quantity</th>
                                    <th scope="col" class="py-3 text-end">Subtotal</th>
                                    <th scope="col" class="pe-4 py-3 text-center" style="width: 60px;"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($cart as $id => $item)
                                    <tr>
                                        <!-- Product info -->
                                        <td class="ps-4 py-3">
                                            <div class="d-flex align-items-center gap-3">
                                                <div class="rounded p-2 border d-flex align-items-center justify-content-center" style="width: 44px; height: 44px; background: rgba(59, 130, 246, 0.15); border-color: rgba(59, 130, 246, 0.3) !important;">
                                                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#60a5fa" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                        <rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect>
                                                        <line x1="8" y1="21" x2="16" y2="21"></line>
                                                        <line x1="12" y1="17" x2="12" y2="21"></line>
                                                    </svg>
                                                </div>
                                                <div>
                                                    <h6 class="fw-bold mb-0 text-white" style="font-size: 0.98rem; color: #ffffff !important;">
                                                        {{ $item['name'] }}
                                                        @if(!empty($item['discount_percentage']) && $item['discount_percentage'] > 0)
                                                            <span class="badge bg-danger ms-1" style="font-size: 0.7rem;">{{ $item['discount_percentage'] }}% OFF</span>
                                                        @endif
                                                    </h6>
                                                    @if(!empty($item['description']))
                                                        <small class="text-muted d-block" style="max-width: 260px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                                            {{ $item['description'] }}
                                                        </small>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>

                                        <!-- Unit Price -->
                                        <td class="text-center fw-semibold text-secondary">
                                            @if(!empty($item['discount_percentage']) && $item['discount_percentage'] > 0)
                                                <div>
                                                    <span class="fw-bold text-white">₹{{ number_format($item['price'], 2) }}</span>
                                                    <small class="text-muted text-decoration-line-through d-block" style="font-size: 0.75rem;">₹{{ number_format($item['original_price'], 2) }}</small>
                                                </div>
                                            @else
                                                <span class="text-white">₹{{ number_format($item['price'], 2) }}</span>
                                            @endif
                                        </td>

                                        <!-- Quantity Update Form -->
                                        <td class="text-center">
                                            @php
                                                $prodModel = \App\Models\Product::find($id);
                                                $extraStock = $prodModel ? $prodModel->stock : 0;
                                                $maxQuantityAllowed = $item['quantity'] + $extraStock;
                                            @endphp
                                            <form action="{{ route('cart.update', $id) }}" method="POST" class="d-flex flex-column align-items-center justify-content-center gap-1">
                                                @csrf
                                                <div class="d-flex align-items-center gap-1">
                                                    <input 
                                                        type="number" 
                                                        name="quantity" 
                                                        value="{{ $item['quantity'] }}" 
                                                        min="1" 
                                                        max="{{ $maxQuantityAllowed }}" 
                                                        class="form-control form-control-sm text-center" 
                                                        style="width: 65px;"
                                                        required
                                                    >
                                                    <button type="submit" class="btn btn-outline-primary btn-sm py-1 px-2" title="Update Quantity">
                                                        ✓
                                                    </button>
                                                </div>
                                                @if($extraStock > 0)
                                                    <small class="text-muted" style="font-size: 0.72rem;">+{{ $extraStock }} in stock</small>
                                                @else
                                                    <small class="text-warning fw-semibold" style="font-size: 0.72rem;">Max limit</small>
                                                @endif
                                            </form>
                                        </td>

                                        <!-- Line Subtotal -->
                                        <td class="text-end fw-bold text-success">
                                            ₹{{ number_format($item['price'] * $item['quantity'], 2) }}
                                        </td>

                                        <!-- Remove Item Form -->
                                        <td class="pe-4 text-center">
                                            <form action="{{ route('cart.remove', $id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-outline-danger btn-sm p-1 rounded-circle" style="width: 32px; height: 32px;" title="Remove Item">
                                                    ✕
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Card Footer Actions -->
                <div class="card-footer d-flex justify-content-between align-items-center py-3 px-4 border-top">
                    <a href="{{ route('products.index') }}" class="btn btn-outline-secondary btn-sm">
                        + Add More Products
                    </a>

                    <form action="{{ route('cart.clear') }}" method="POST" class="m-0">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger btn-sm">
                            Clear Entire Cart
                        </button>
                    </form>
                </div>
            </div>

            <!-- Available Coupons Banner Card -->
            @if(isset($availableCoupons) && count($availableCoupons) > 0)
                <div class="card border-0 shadow-sm p-3">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <span class="fs-5">🎟️</span>
                        <h6 class="fw-bold mb-0 text-white">Available Offers & Promo Coupons</h6>
                    </div>
                    <div class="row g-2">
                        @foreach($availableCoupons as $c)
                            <div class="col-md-6">
                                <div class="p-2 border rounded d-flex justify-content-between align-items-center" style="background: rgba(255, 255, 255, 0.03);">
                                    <div>
                                        <span class="badge bg-primary-subtle text-info fw-bold font-monospace me-1">{{ $c->code }}</span>
                                        <small class="text-muted d-block mt-1" style="font-size: 0.78rem;">
                                            @if($c->type === 'percent')
                                                {{ $c->value }}% OFF @if($c->max_discount_amount)(Max ₹{{ $c->max_discount_amount }})@endif
                                            @else
                                                Flat ₹{{ number_format($c->value, 0) }} OFF
                                            @endif
                                            @if($c->min_cart_amount)
                                                (Min ₹{{ number_format($c->min_cart_amount, 0) }})
                                            @endif
                                        </small>
                                    </div>
                                    <form action="{{ route('cart.coupon.apply') }}" method="POST" class="m-0">
                                        @csrf
                                        <input type="hidden" name="coupon_code" value="{{ $c->code }}">
                                        <button type="submit" class="btn btn-outline-primary btn-sm py-1 px-2" style="font-size: 0.75rem;">
                                            Apply
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <!-- Order Summary & Checkout Action (Col 4) -->
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 sticky-top" style="top: 5rem;">
                <div class="card-header py-3">
                    <h5 class="fw-bold mb-0 text-white">Order Summary</h5>
                </div>
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between mb-2 text-muted">
                        <span>Total Items</span>
                        <span class="fw-semibold text-white">{{ array_sum(array_column($cart, 'quantity')) }} items</span>
                    </div>

                    <div class="d-flex justify-content-between mb-2 text-muted">
                        <span>Subtotal</span>
                        <span class="fw-semibold text-white">₹{{ number_format($subtotal, 2) }}</span>
                    </div>

                    <!-- Coupon Section inside Summary -->
                    <div class="my-3 py-3 border-top border-bottom">
                        <label class="form-label fw-bold text-white mb-2" style="font-size: 0.85rem;">Promo Code / Coupon</label>
                        @if(session('coupon'))
                            <div class="d-flex justify-content-between align-items-center p-2 rounded border border-success mb-2" style="background: rgba(34, 197, 94, 0.12);">
                                <div>
                                    <span class="fw-bold font-monospace text-success">🎟️ {{ session('coupon.code') }}</span>
                                    <small class="d-block text-success" style="font-size: 0.75rem;">Discount: -₹{{ number_format($discountAmount, 2) }}</small>
                                </div>
                                <form action="{{ route('cart.coupon.remove') }}" method="POST" class="m-0">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-danger py-0 px-2" style="font-size: 0.75rem;" title="Remove Coupon">
                                        Remove
                                    </button>
                                </form>
                            </div>
                        @else
                            <form action="{{ route('cart.coupon.apply') }}" method="POST" class="d-flex gap-2">
                                @csrf
                                <input 
                                    type="text" 
                                    name="coupon_code" 
                                    class="form-control form-control-sm text-uppercase font-monospace" 
                                    placeholder="Enter Code (e.g. WELCOME20)"
                                    required
                                >
                                <button type="submit" class="btn btn-primary btn-sm px-3 fw-bold">
                                    Apply
                                </button>
                            </form>
                        @endif
                    </div>

                    @if($discountAmount > 0)
                        <div class="d-flex justify-content-between mb-2 text-success fw-semibold">
                            <span>Coupon Discount</span>
                            <span>- ₹{{ number_format($discountAmount, 2) }}</span>
                        </div>
                    @endif

                    <div class="d-flex justify-content-between mb-3 text-muted">
                        <span>Shipping & Delivery</span>
                        <span class="badge bg-success-subtle text-success fw-bold px-2 py-1">FREE</span>
                    </div>

                    <hr class="my-3">

                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <span class="h6 fw-bold mb-0 text-white">Total Amount</span>
                        <span class="h4 fw-bold text-success mb-0">₹{{ number_format($total, 2) }}</span>
                    </div>

                    <!-- Payment Checkout Form -->
                    <form action="{{ route('payment.create') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-primary w-100 py-3 fw-bold fs-6 d-flex align-items-center justify-content-center gap-2 shadow-sm">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>
                                <line x1="1" y1="10" x2="23" y2="10"></line>
                            </svg>
                            <span>Buy / Proceed to Checkout</span>
                        </button>
                    </form>

                    <div class="text-center mt-3">
                        <small class="text-muted d-flex align-items-center justify-content-center gap-1">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-success">
                                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                            </svg>
                            <span>100% Safe & Secure Payment via Razorpay</span>
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
@else
    <!-- Empty Cart State -->
    <div class="card border-0 shadow-sm text-center py-5">
        <div class="card-body py-5">
            <div class="rounded-circle p-4 d-inline-flex align-items-center justify-content-center mb-3 border" style="background: rgba(255, 255, 255, 0.05);">
                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="9" cy="21" r="1"></circle>
                    <circle cx="20" cy="21" r="1"></circle>
                    <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                </svg>
            </div>
            <h4 class="fw-bold text-white mb-2">Your Cart is Empty</h4>
            <p class="text-muted mb-4">You haven't added any products to your cart yet.</p>
            <a href="{{ route('products.index') }}" class="btn btn-primary px-4 py-2">
                Browse Products
            </a>
        </div>
    </div>
@endif
@endsection
