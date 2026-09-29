@extends('layouts.app')

@section('title', 'Featured Products - Razorpay Store')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 pb-2 border-bottom">
    <div>
        <h2 class="h3 fw-bold mb-1">Our Products</h2>
        <p class="text-muted mb-0">Browse our collection and purchase directly with Razorpay</p>
    </div>
    <div>
        <span class="badge bg-primary fs-6 px-3 py-2 rounded-pill">
            {{ $products->count() }} Available Products
        </span>
    </div>
</div>

<div class="row g-4">
    @forelse($products as $product)
        <div class="col-sm-6 col-lg-4 col-xl-3">
            <div class="card h-100 product-card">
                <!-- Product Card Header / Visual Area (Pure HTML & CSS - No JS) -->
                <div class="p-4 text-center bg-light border-bottom rounded-top" style="min-height: 140px; display: flex; align-items: center; justify-content: center;">
                    <div class="rounded-circle bg-white p-3 shadow-sm border">
                        <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#2b6cb0" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect>
                            <line x1="8" y1="21" x2="16" y2="21"></line>
                            <line x1="12" y1="17" x2="12" y2="21"></line>
                        </svg>
                    </div>
                </div>

                <!-- Product Body -->
                <div class="card-body d-flex flex-column">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <h5 class="card-title fw-bold text-dark mb-0">
                            {{ $product->product_name }}
                        </h5>
                    </div>

                    <p class="card-text text-muted small flex-grow-1">
                        {{ $product->description ?? 'High quality item ready for instant delivery.' }}
                    </p>

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="price-tag">
                            ₹{{ number_format($product->price, 2) }}
                        </span>

                        @if(($product->stock ?? 0) <= 0)
                            <span class="badge bg-danger text-white badge-stock">
                                🚫 Out of Stock
                            </span>
                        @elseif($product->stock < 5)
                            <span class="badge bg-warning text-dark badge-stock">
                                🔥 Only {{ $product->stock }} left!
                            </span>
                        @else
                            <span class="badge bg-success text-white badge-stock">
                                In Stock ({{ $product->stock }})
                            </span>
                        @endif
                    </div>

                    @if(($product->stock ?? 0) > 0)
                        <!-- Pure HTML Form submission for Cart & Purchase (Strictly No JavaScript) -->
                        <form action="{{ route('cart.add') }}" method="POST">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">

                            <div class="input-group mb-3">
                                <span class="input-group-text bg-white text-muted small">Qty</span>
                                <input 
                                    type="number" 
                                    name="quantity" 
                                    class="form-control text-center" 
                                    value="1" 
                                    min="1" 
                                    max="{{ $product->stock }}"
                                    required
                                >
                            </div>

                            <div class="d-grid gap-2">
                                <button 
                                    type="submit" 
                                    name="action"
                                    value="add"
                                    class="btn btn-primary d-flex align-items-center justify-content-center gap-2"
                                >
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <circle cx="9" cy="21" r="1"></circle>
                                        <circle cx="20" cy="21" r="1"></circle>
                                        <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                                    </svg>
                                    <span>Add to Cart</span>
                                </button>

                                <button 
                                    type="submit" 
                                    name="action"
                                    value="buy_now"
                                    class="btn btn-outline-success d-flex align-items-center justify-content-center gap-2 btn-sm fw-semibold"
                                >
                                    <span>⚡ Buy Now</span>
                                </button>
                            </div>
                        </form>
                    @else
                        <!-- Out of stock notice for customer -->
                        <div class="text-center p-3 bg-light rounded border border-danger-subtle mt-auto">
                            <div class="text-danger fw-bold mb-1" style="font-size: 0.9rem;">
                                ⚠️ Item Out of Stock
                            </div>
                            <small class="text-muted d-block mb-3" style="font-size: 0.8rem;">
                                Currently unavailable for purchase.
                            </small>
                            <button type="button" class="btn btn-secondary w-100 btn-sm disabled" disabled>
                                Sold Out
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @empty
        <div class="col-12 text-center py-5">
            <div class="card p-5 border-dashed">
                <h4 class="text-muted">No products found</h4>
                <p class="text-secondary">Please check back later or seed the products table.</p>
            </div>
        </div>
    @endforelse
</div>
@endsection