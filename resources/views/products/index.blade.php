@extends('layouts.app')

@section('title', 'Our Products — Razorpay Store')

@section('content')

{{-- Page Header --}}
<div class="products-page-header mb-5">
    <div class="products-page-header-inner">
        <div class="section-eyebrow">🛍️ Full Catalog</div>
        <h1 class="products-page-title">Our Products</h1>
        <p class="products-page-sub">Browse our collection and purchase securely with Razorpay</p>
    </div>
    <div class="products-page-badge">
        <span class="products-count-badge">{{ $products->count() }} Products</span>
    </div>
</div>

<div class="row g-4">
    @forelse($products as $product)
        <div class="col-sm-6 col-lg-4 col-xl-3">
            <div class="pcard h-100">
                {{-- Smart Product Image --}}
                <div class="pcard-img-wrap">
                    <img
                        src="{{ $product->displayImage() }}"
                        alt="{{ $product->product_name }}"
                        class="pcard-img"
                        loading="lazy"
                    >
                    {{-- Discount badge overlay --}}
                    @if($product->hasDiscount())
                        <div class="pcard-badge pcard-badge--danger" style="left: 12px; right: auto; background: #dc2626;">
                            🏷️ {{ $product->discount_percentage }}% OFF
                        </div>
                    @endif

                    {{-- Stock badge overlay --}}
                    @if(($product->stock ?? 0) <= 0)
                        <div class="pcard-badge pcard-badge--danger">Out of Stock</div>
                    @elseif($product->stock < 5)
                        <div class="pcard-badge pcard-badge--warn">🔥 {{ $product->stock }} left</div>
                    @else
                        <div class="pcard-badge pcard-badge--success">✓ In Stock</div>
                    @endif
                </div>

                {{-- Card Body --}}
                <div class="pcard-body">
                    <h5 class="pcard-name">{{ $product->product_name }}</h5>
                    <p class="pcard-desc">
                        {{ Str::limit($product->description ?? 'Premium quality item ready for instant delivery.', 72) }}
                    </p>

                    <div class="pcard-price-row d-flex align-items-baseline gap-2">
                        <span class="pcard-price">₹{{ number_format($product->finalPrice(), 2) }}</span>
                        @if($product->hasDiscount())
                            <span class="text-muted text-decoration-line-through fw-semibold" style="font-size: 0.9rem;">
                                ₹{{ number_format($product->price, 2) }}
                            </span>
                        @endif
                    </div>

                    @if(($product->stock ?? 0) > 0)
                        <form action="{{ route('cart.add') }}" method="POST" class="pcard-form">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <div class="pcard-qty-row">
                                <label class="pcard-qty-label">Qty</label>
                                <input
                                    type="number"
                                    name="quantity"
                                    class="pcard-qty-input"
                                    value="1"
                                    min="1"
                                    max="{{ $product->stock }}"
                                    required
                                >
                            </div>
                            <div class="pcard-actions">
                                <button type="submit" name="action" value="add" class="pcard-btn-cart"
                                        id="pcard-cart-{{ $product->id }}">
                                    🛒 Add to Cart
                                </button>
                                <button type="submit" name="action" value="buy_now" class="pcard-btn-buy"
                                        id="pcard-buy-{{ $product->id }}">
                                    ⚡ Buy Now
                                </button>
                            </div>
                        </form>
                    @else
                        <div class="pcard-soldout">
                            <span>😔 Currently Unavailable</span>
                            <button class="pcard-btn-disabled" disabled>Sold Out</button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @empty
        <div class="col-12 text-center py-5">
            <div class="empty-state">
                <div class="empty-state-icon">📦</div>
                <h4>No products found</h4>
                <p class="text-muted">Please check back later.</p>
            </div>
        </div>
    @endforelse
</div>

<style>
/* ── Products Page Header ─── */
.products-page-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
    flex-wrap: wrap;
    gap: 1rem;
    padding-bottom: 1.5rem;
    border-bottom: 2px solid var(--border-color);
}
.products-page-title {
    font-size: clamp(1.8rem, 4vw, 2.6rem);
    font-weight: 900;
    color: var(--text-main);
    margin: 0.3rem 0 0.25rem;
    letter-spacing: -1px;
}
.products-page-sub {
    color: var(--text-sub);
    margin: 0;
    font-size: 1rem;
}
.products-count-badge {
    background: linear-gradient(135deg, #3b82f6, #6366f1);
    color: #fff;
    font-weight: 700;
    font-size: 0.9rem;
    padding: 0.5rem 1.4rem;
    border-radius: 50px;
    display: inline-block;
}

/* ── Product Card ─── */
.pcard {
    background: var(--bg-card);
    border: 1px solid var(--border-color);
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 4px 20px rgba(0,0,0,0.2);
    transition: transform 0.28s cubic-bezier(.22,.68,0,1.4), box-shadow 0.28s ease;
    display: flex;
    flex-direction: column;
}
.pcard:hover {
    transform: translateY(-8px) scale(1.01);
    box-shadow: 0 20px 48px rgba(0,0,0,0.4);
}

.pcard-img-wrap {
    position: relative;
    height: 190px;
    overflow: hidden;
    background: #0d1322;
}
.pcard-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.4s ease;
}
.pcard:hover .pcard-img {
    transform: scale(1.08);
}

/* Stock badges overlay */
.pcard-badge {
    position: absolute;
    top: 12px;
    right: 12px;
    font-size: 0.72rem;
    font-weight: 700;
    padding: 0.28rem 0.75rem;
    border-radius: 50px;
    backdrop-filter: blur(6px);
    letter-spacing: 0.3px;
}
.pcard-badge--success { background: rgba(16,185,129,0.9); color:#fff; }
.pcard-badge--warn    { background: rgba(245,158,11,0.92); color:#fff; }
.pcard-badge--danger  { background: rgba(239,68,68,0.9);  color:#fff; }

.pcard-body {
    padding: 1.25rem 1.25rem 1.4rem;
    display: flex;
    flex-direction: column;
    flex: 1;
}
.pcard-name {
    font-size: 1.05rem;
    font-weight: 800;
    color: var(--text-main);
    margin-bottom: 0.4rem;
}
.pcard-desc {
    font-size: 0.82rem;
    color: var(--text-sub);
    line-height: 1.55;
    flex-grow: 1;
    margin-bottom: 0.85rem;
}
.pcard-price-row {
    margin-bottom: 0.85rem;
}
.pcard-price {
    font-size: 1.55rem;
    font-weight: 900;
    color: #10b981;
    letter-spacing: -0.5px;
}

/* Qty row */
.pcard-form { display: flex; flex-direction: column; gap: 0.6rem; }
.pcard-qty-row {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    background: var(--bg-input);
    border: 1px solid var(--border-color);
    border-radius: 10px;
    padding: 0.35rem 0.75rem;
}
.pcard-qty-label { font-size: 0.78rem; font-weight: 600; color: var(--text-sub); white-space: nowrap; }
.pcard-qty-input {
    border: none;
    background: transparent;
    font-size: 0.9rem;
    font-weight: 700;
    color: var(--text-main);
    text-align: center;
    width: 100%;
    outline: none;
}

/* Action buttons */
.pcard-actions { display: grid; grid-template-columns: 1fr 1fr; gap: 0.5rem; }
.pcard-btn-cart {
    background: transparent;
    border: 1.5px solid #3b82f6;
    color: #60a5fa;
    font-weight: 700;
    font-size: 0.82rem;
    padding: 0.6rem 0.5rem;
    border-radius: 10px;
    cursor: pointer;
    transition: all 0.2s ease;
}
.pcard-btn-cart:hover { background: #3b82f6; color: #fff; }
.pcard-btn-buy {
    background: linear-gradient(135deg, #3b82f6, #6366f1);
    border: none;
    color: #fff;
    font-weight: 700;
    font-size: 0.82rem;
    padding: 0.6rem 0.5rem;
    border-radius: 10px;
    cursor: pointer;
    transition: all 0.2s ease;
    box-shadow: 0 4px 12px rgba(99,102,241,0.35);
}
.pcard-btn-buy:hover { opacity: 0.88; transform: translateY(-1px); }

/* Sold out state */
.pcard-soldout {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
    margin-top: auto;
}
.pcard-soldout span { font-size: 0.82rem; color: #ef4444; font-weight: 600; }
.pcard-btn-disabled {
    background: var(--bg-input);
    border: 1px solid var(--border-color);
    color: var(--text-sub);
    font-size: 0.85rem;
    padding: 0.6rem;
    border-radius: 10px;
    cursor: not-allowed;
    width: 100%;
}

/* Empty state */
.empty-state {
    background: var(--bg-card);
    border: 2px dashed var(--border-color);
    border-radius: 20px;
    padding: 4rem 2rem;
}
.empty-state-icon { font-size: 3rem; margin-bottom: 1rem; }
</style>

@endsection