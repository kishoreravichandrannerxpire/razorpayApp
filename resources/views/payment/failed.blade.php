@extends('layouts.app')

@section('title', 'Payment Failed - Razorpay Store')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6 text-center">
        <div class="card p-5 shadow-sm border-0">
            <div class="mb-4">
                <div class="rounded-circle bg-danger text-white d-inline-flex align-items-center justify-content-center" style="width: 72px; height: 72px; font-size: 32px;">
                    ✕
                </div>
            </div>

            <h2 class="fw-bold text-danger mb-2">Payment Failed</h2>
            <p class="text-muted mb-4">We were unable to process your payment. Please try again or choose another payment option.</p>

            <div class="d-flex justify-content-center gap-3">
                <a href="{{ route('products.index') }}" class="btn btn-outline-secondary px-4 py-2">
                    Back to Products
                </a>
            </div>
        </div>
    </div>
</div>
@endsection