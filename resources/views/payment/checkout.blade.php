@extends('layouts.app')

@section('title', 'Checkout & Payment - Razorpay Store')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10">

        <!-- Page Header -->
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 pb-2 border-bottom">
            <div>
                <h2 class="h3 fw-bold mb-1">Order Checkout</h2>
                <p class="text-muted mb-0">Confirm your product details and proceed to secure payment</p>
            </div>
            <div>
                <a href="{{ route('cart.index') }}" class="btn btn-outline-secondary btn-sm">
                    &larr; Back to Cart
                </a>
            </div>
        </div>

        <div class="row g-4">
            <!-- Customer & Order Information (Col 5) -->
            <div class="col-lg-5">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-light py-3">
                        <h5 class="fw-bold mb-0 text-dark">Customer Details</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="mb-3 pb-2 border-bottom">
                            <small class="text-muted d-block">Order Reference</small>
                            <span class="fw-bold text-dark fs-6">{{ $order->order_number }}</span>
                        </div>

                        <div class="mb-3 pb-2 border-bottom">
                            <small class="text-muted d-block">Full Name</small>
                            <span class="fw-semibold text-dark">{{ $order->user->name ?? Auth::user()->name }}</span>
                        </div>

                        <div class="mb-3 pb-2 border-bottom">
                            <small class="text-muted d-block">Email Address</small>
                            <span class="fw-semibold text-dark">{{ $order->user->email ?? Auth::user()->email }}</span>
                        </div>

                        @if(!empty($order->user->phone ?? Auth::user()->phone))
                            <div class="mb-3 pb-2 border-bottom">
                                <small class="text-muted d-block">Phone Number</small>
                                <span class="fw-semibold text-dark">{{ $order->user->phone ?? Auth::user()->phone }}</span>
                            </div>
                        @endif

                        <div class="mb-0">
                            <small class="text-muted d-block">Payment Status</small>
                            <span class="badge bg-warning text-dark px-3 py-2 mt-1">
                                ⏳ {{ $order->status }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Safe & Secure Payment Card -->
                <div class="card shadow-sm border-0 bg-light">
                    <div class="card-body p-3 text-center">
                        <div class="d-flex align-items-center justify-content-center gap-2 text-success fw-bold mb-1">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                                <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                            </svg>
                            <span>Safe & Encrypted Checkout</span>
                        </div>
                        <small class="text-muted">
                            Secured by Razorpay Payments. Supports UPI, Cards, Netbanking & Wallets.
                        </small>
                    </div>
                </div>
            </div>

            <!-- Product Details & Summary (Col 7) -->
            <div class="col-lg-7">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-light py-3 d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold mb-0 text-dark">Purchased Products</h5>
                        <span class="badge bg-primary rounded-pill">
                            {{ $order->orderItems->count() }} {{ Str::plural('Item', $order->orderItems->count()) }}
                        </span>
                    </div>

                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-4 py-3">Product Name</th>
                                        <th class="py-3 text-center">Unit Price</th>
                                        <th class="py-3 text-center">Qty</th>
                                        <th class="pe-4 py-3 text-end">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($order->orderItems as $item)
                                        <tr>
                                            <td class="ps-4 py-3">
                                                <div class="d-flex align-items-center gap-2">
                                                    <div class="rounded bg-light p-2 border d-flex align-items-center justify-content-center" style="width: 38px; height: 38px;">
                                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#2b6cb0" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                            <rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect>
                                                            <line x1="8" y1="21" x2="16" y2="21"></line>
                                                            <line x1="12" y1="17" x2="12" y2="21"></line>
                                                        </svg>
                                                    </div>
                                                    <div>
                                                        <span class="fw-bold text-dark d-block">
                                                            {{ $item->product->product_name ?? 'Product' }}
                                                        </span>
                                                        @if(!empty($item->product->description))
                                                            <small class="text-muted d-block" style="max-width: 220px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                                                {{ $item->product->description }}
                                                            </small>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center text-secondary fw-semibold">
                                                ₹{{ number_format($item->price, 2) }}
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-light text-dark border px-2 py-1 fs-6">
                                                    {{ $item->quantity }}
                                                </span>
                                            </td>
                                            <td class="pe-4 text-end fw-bold text-success">
                                                ₹{{ number_format($item->subtotal, 2) }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center py-4 text-muted">
                                                No items found in this order.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Price Calculation Breakdown -->
                    <div class="card-footer bg-white p-4 border-top">
                        <div class="d-flex justify-content-between mb-2 text-muted">
                            <span>Items Total</span>
                            <span class="fw-semibold text-dark">₹{{ number_format($order->total_amount, 2) }}</span>
                        </div>

                        <div class="d-flex justify-content-between mb-3 text-muted">
                            <span>Shipping & Handling</span>
                            <span class="badge bg-success-subtle text-success fw-bold px-2 py-1">FREE</span>
                        </div>

                        <hr class="my-3">

                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <span class="h5 fw-bold text-dark mb-0">Total Payable Amount</span>
                            <span class="h3 fw-bold text-success mb-0">₹{{ number_format($order->total_amount, 2) }}</span>
                        </div>

                        <!-- Pay Button -->
                        <button id="rzp-button" type="button" class="btn btn-primary w-100 py-3 fw-bold fs-5 shadow-sm d-flex align-items-center justify-content-center gap-2">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>
                                <line x1="1" y1="10" x2="23" y2="10"></line>
                            </svg>
                            <span>Pay ₹{{ number_format($order->total_amount, 2) }} with Razorpay</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- Hidden Razorpay Signature Verification Form -->
<form action="{{ route('payment.verify') }}" method="POST" id="payment-form" class="d-none">
    @csrf
    <input type="hidden" name="razorpay_payment_id" id="razorpay_payment_id">
    <input type="hidden" name="razorpay_order_id" id="razorpay_order_id" value="{{ $razorpayOrder['id'] }}">
    <input type="hidden" name="razorpay_signature" id="razorpay_signature">
</form>

<!-- Razorpay Checkout Gateway SDK (Only for gateway popup modal) -->
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
var options = {
    key: "{{ config('services.razorpay.key') }}",
    amount: "{{ $razorpayOrder['amount'] }}",
    currency: "INR",
    name: "Razorpay Store",
    description: "Payment for Order #{{ $order->order_number }}",
    order_id: "{{ $razorpayOrder['id'] }}",
    prefill: {
        name: "{{ $order->user->name ?? Auth::user()->name }}",
        email: "{{ $order->user->email ?? Auth::user()->email }}",
        contact: "{{ $order->user->phone ?? Auth::user()->phone ?? '' }}"
    },
    theme: {
        color: "#2b6cb0"
    },
    handler: function (response) {
        document.getElementById('razorpay_payment_id').value = response.razorpay_payment_id;
        document.getElementById('razorpay_order_id').value = response.razorpay_order_id;
        document.getElementById('razorpay_signature').value = response.razorpay_signature;
        document.getElementById('payment-form').submit();
    }
};

var rzp = new Razorpay(options);

document.getElementById('rzp-button').onclick = function(e){
    rzp.open();
    e.preventDefault();
};
</script>
@endsection