@extends('layouts.app')

@section('title', 'Payment Successful - Razorpay Store')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6 text-center">
        <div class="card p-5 shadow-sm border-0">
            <div class="mb-4">
                <div class="rounded-circle bg-success text-white d-inline-flex align-items-center justify-content-center" style="width: 72px; height: 72px; font-size: 32px;">
                    ✓
                </div>
            </div>

            <h2 class="fw-bold text-success mb-2">Payment Successful!</h2>
            <p class="text-muted mb-4">Thank you for your purchase. Your payment has been processed and confirmed.</p>

            <div>
                <a href="{{ route('products.index') }}" class="btn btn-primary px-4 py-2">
                    Continue Shopping
                </a>
            </div>
        </div>
    </div>
</div>
@endsection