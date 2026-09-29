@extends('layouts.app')

@section('title', 'Shopping Cart - Razorpay Store')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 pb-2 border-bottom">
    <div>
        <h2 class="h3 fw-bold mb-1">Shopping Cart</h2>
        <p class="text-muted mb-0">Review your selected items before proceeding to checkout</p>
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
            <div class="card shadow-sm border-0">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
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
                                                <div class="rounded bg-light p-2 border d-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
                                                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#2b6cb0" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                        <rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect>
                                                        <line x1="8" y1="21" x2="16" y2="21"></line>
                                                        <line x1="12" y1="17" x2="12" y2="21"></line>
                                                    </svg>
                                                </div>
                                                <div>
                                                    <h6 class="fw-bold mb-0 text-dark">{{ $item['name'] }}</h6>
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
                                            ₹{{ number_format($item['price'], 2) }}
                                        </td>

                                        <!-- Quantity Update Form (Pure HTML - No JS) -->
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
                                                    <small class="text-muted" style="font-size: 0.72rem;">+{{ $extraStock }} more in stock</small>
                                                @else
                                                    <small class="text-warning fw-semibold" style="font-size: 0.72rem;">Max available</small>
                                                @endif
                                            </form>
                                        </td>

                                        <!-- Line Subtotal -->
                                        <td class="text-end fw-bold text-success">
                                            ₹{{ number_format($item['price'] * $item['quantity'], 2) }}
                                        </td>

                                        <!-- Remove Item Form (Pure HTML - No JS) -->
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
                <div class="card-footer bg-white d-flex justify-content-between align-items-center py-3 px-4 border-top">
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
        </div>

        <!-- Order Summary & Checkout Action (Col 4) -->
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 sticky-top" style="top: 2rem;">
                <div class="card-header bg-light py-3">
                    <h5 class="fw-bold mb-0 text-dark">Order Summary</h5>
                </div>
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between mb-2 text-muted">
                        <span>Total Items</span>
                        <span class="fw-semibold text-dark">{{ array_sum(array_column($cart, 'quantity')) }} items</span>
                    </div>

                    <div class="d-flex justify-content-between mb-2 text-muted">
                        <span>Items Subtotal</span>
                        <span class="fw-semibold text-dark">₹{{ number_format($total, 2) }}</span>
                    </div>

                    <div class="d-flex justify-content-between mb-3 text-muted">
                        <span>Shipping & Delivery</span>
                        <span class="badge bg-success-subtle text-success fw-bold px-2 py-1">FREE</span>
                    </div>

                    <hr class="my-3">

                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <span class="h6 fw-bold mb-0 text-dark">Total Amount</span>
                        <span class="h4 fw-bold text-success mb-0">₹{{ number_format($total, 2) }}</span>
                    </div>

                    <!-- Pure HTML Form to trigger Payment Checkout (Strictly No JS) -->
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
            <div class="rounded-circle bg-light p-4 d-inline-flex align-items-center justify-content-center mb-3 border">
                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#6c757d" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="9" cy="21" r="1"></circle>
                    <circle cx="20" cy="21" r="1"></circle>
                    <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                </svg>
            </div>
            <h4 class="fw-bold text-dark mb-2">Your Cart is Empty</h4>
            <p class="text-muted mb-4">You haven't added any products to your cart yet.</p>
            <a href="{{ route('products.index') }}" class="btn btn-primary px-4 py-2">
                Browse Products
            </a>
        </div>
    </div>
@endif
@endsection
