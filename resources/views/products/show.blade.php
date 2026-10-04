@extends('layouts.app')

@section('title', $product->product_name . ' — Store')

@section('content')

{{-- ── Full-Bleed Hero ──────────────────────────────────────────── --}}
<div class="pd-hero" style="--hero-img: url('{{ $product->displayImage() }}');">

    {{-- Dark overlay gradient --}}
    <div class="pd-hero-overlay"></div>

    {{-- Nav arrows inside hero --}}
    <div class="pd-hero-nav">
        @if(isset($prev))
            <a href="{{ route('products.show', $prev->id) }}" class="pd-nav-btn pd-nav-prev" title="{{ $prev->product_name }}">
                <span class="pd-nav-icon">&#8592;</span>
                <span class="pd-nav-label">{{ Str::limit($prev->product_name, 18) }}</span>
            </a>
        @else
            <span></span>
        @endif

        @if(isset($next))
            <a href="{{ route('products.show', $next->id) }}" class="pd-nav-btn pd-nav-next" title="{{ $next->product_name }}">
                <span class="pd-nav-label">{{ Str::limit($next->product_name, 18) }}</span>
                <span class="pd-nav-icon">&#8594;</span>
            </a>
        @endif
    </div>

    {{-- Product title + badges floating on image --}}
    <div class="pd-hero-content">
        <div class="pd-badges">
            @if($product->hasDiscount())
                <span class="pd-badge pd-badge-discount">🏷️ {{ $product->discount_percentage }}% OFF</span>
            @endif
            @if(($product->stock ?? 0) <= 0)
                <span class="pd-badge pd-badge-out">Out of Stock</span>
            @elseif($product->stock < 5)
                <span class="pd-badge pd-badge-low">🔥 Only {{ $product->stock }} left</span>
            @else
                <span class="pd-badge pd-badge-in">✓ In Stock</span>
            @endif
        </div>
        <h1 class="pd-hero-title">{{ $product->product_name }}</h1>
        <div class="pd-price-row">
            <span class="pd-price">₹{{ number_format($product->finalPrice(), 2) }}</span>
            @if($product->hasDiscount())
                <span class="pd-price-old">₹{{ number_format($product->price, 2) }}</span>
            @endif
        </div>
    </div>
</div>

{{-- ── Details Panel ───────────────────────────────────────────── --}}
<div class="pd-panel">
    <p class="pd-desc">{{ $product->description ?? 'Premium quality item ready for instant delivery.' }}</p>

    @if(($product->stock ?? 0) > 0)
        <form action="{{ route('cart.add') }}" method="POST" class="pd-form">
            @csrf
            <input type="hidden" name="product_id" value="{{ $product->id }}">
            <div class="pd-qty-row">
                <label class="pd-qty-label">Quantity</label>
                <div class="pd-qty-ctrl">
                    <button type="button" class="pd-qty-btn" id="qty-minus">−</button>
                    <input type="number" id="qty-input" name="quantity" value="1" min="1" max="{{ $product->stock }}" class="pd-qty-input" required>
                    <button type="button" class="pd-qty-btn" id="qty-plus">+</button>
                </div>
                <span class="pd-stock-info">{{ $product->stock }} available</span>
            </div>
            <div class="pd-actions">
                <button type="submit" name="action" value="add" class="pd-btn-cart">
                    🛒 Add to Cart
                </button>
                <button type="submit" name="action" value="buy_now" class="pd-btn-buy">
                    ⚡ Buy Now
                </button>
            </div>
        </form>
    @else
        <div class="pd-out-msg">
            😔 This product is currently out of stock. Check back soon.
        </div>
    @endif

    {{-- Thumbnail nav strip --}}
    @if(isset($prev) || isset($next))
    <div class="pd-thumb-strip">
        @if(isset($prev))
        <a href="{{ route('products.show', $prev->id) }}" class="pd-thumb-link">
            <img src="{{ $prev->displayImage() }}" alt="{{ $prev->product_name }}" class="pd-thumb-img">
            <span class="pd-thumb-label">← {{ Str::limit($prev->product_name, 20) }}</span>
        </a>
        @endif
        @if(isset($next))
        <a href="{{ route('products.show', $next->id) }}" class="pd-thumb-link">
            <img src="{{ $next->displayImage() }}" alt="{{ $next->product_name }}" class="pd-thumb-img">
            <span class="pd-thumb-label">{{ Str::limit($next->product_name, 20) }} →</span>
        </a>
        @endif
    </div>
    @endif
</div>

<style>
/* ── Reset container padding for full bleed ─── */
.main-content { padding: 0 !important; }

/* ── Hero Section ──────────────────────────────────────── */
.pd-hero {
    position: relative;
    width: 100%;
    height: 70vh;
    min-height: 440px;
    background-image: var(--hero-img);
    background-size: cover;
    background-position: center;
    display: flex;
    flex-direction: column;
    justify-content: flex-end;
    overflow: hidden;
}

.pd-hero-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(
        to bottom,
        rgba(10,14,26,0.15) 0%,
        rgba(10,14,26,0.15) 40%,
        rgba(10,14,26,0.85) 80%,
        rgba(10,14,26,1)    100%
    );
    z-index: 1;
}

/* nav arrows floating on corners */
.pd-hero-nav {
    position: absolute;
    top: 50%;
    left: 0; right: 0;
    transform: translateY(-50%);
    display: flex;
    justify-content: space-between;
    padding: 0 1.5rem;
    z-index: 10;
    pointer-events: none;
}
.pd-nav-btn {
    pointer-events: all;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    background: rgba(255,255,255,0.12);
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    border: 1px solid rgba(255,255,255,0.25);
    color: #fff;
    text-decoration: none;
    padding: 0.6rem 1.1rem;
    border-radius: 50px;
    font-size: 0.82rem;
    font-weight: 700;
    letter-spacing: 0.3px;
    transition: background 0.2s, transform 0.2s;
    max-width: 180px;
}
.pd-nav-btn:hover {
    background: rgba(255,255,255,0.25);
    color: #fff;
    transform: scale(1.04);
}
.pd-nav-icon { font-size: 1.1rem; }

/* hero text area */
.pd-hero-content {
    position: relative;
    z-index: 5;
    padding: 2rem 2rem 0;
}
.pd-badges {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
    margin-bottom: 0.75rem;
}
.pd-badge {
    font-size: 0.75rem;
    font-weight: 700;
    padding: 0.3rem 0.85rem;
    border-radius: 50px;
    letter-spacing: 0.4px;
}
.pd-badge-discount { background: #dc2626; color: #fff; }
.pd-badge-in       { background: rgba(16,185,129,0.9); color: #fff; }
.pd-badge-low      { background: rgba(245,158,11,0.95); color: #fff; }
.pd-badge-out      { background: rgba(100,100,120,0.9); color: #fff; }

.pd-hero-title {
    font-size: clamp(1.6rem, 4vw, 2.8rem);
    font-weight: 900;
    color: #fff;
    margin: 0 0 0.5rem;
    line-height: 1.15;
    letter-spacing: -0.5px;
    text-shadow: 0 2px 12px rgba(0,0,0,0.5);
}
.pd-price-row {
    display: flex;
    align-items: baseline;
    gap: 1rem;
    padding-bottom: 1rem;
}
.pd-price {
    font-size: 2rem;
    font-weight: 900;
    color: #7c3aed;
    text-shadow: 0 0 20px rgba(124,58,237,0.5);
}
.pd-price-old {
    font-size: 1.1rem;
    color: rgba(255,255,255,0.5);
    text-decoration: line-through;
}

/* ── Details Panel below hero ──────────────────────────── */
.pd-panel {
    background: var(--bg-card, #0f1523);
    padding: 2rem;
}
.pd-desc {
    color: var(--text-sub, #a0aec0);
    font-size: 1.05rem;
    line-height: 1.75;
    margin-bottom: 2rem;
    max-width: 700px;
}

/* Quantity Row */
.pd-qty-row {
    display: flex;
    align-items: center;
    gap: 1.25rem;
    margin-bottom: 1.5rem;
    flex-wrap: wrap;
}
.pd-qty-label {
    color: var(--text-sub, #a0aec0);
    font-size: 0.9rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}
.pd-qty-ctrl {
    display: flex;
    align-items: center;
    background: rgba(255,255,255,0.06);
    border: 1px solid rgba(255,255,255,0.12);
    border-radius: 12px;
    overflow: hidden;
}
.pd-qty-btn {
    background: none;
    border: none;
    color: #fff;
    font-size: 1.3rem;
    width: 44px;
    height: 44px;
    cursor: pointer;
    transition: background 0.15s;
    display: flex; align-items: center; justify-content: center;
}
.pd-qty-btn:hover { background: rgba(255,255,255,0.1); }
.pd-qty-input {
    width: 56px;
    text-align: center;
    background: none;
    border: none;
    color: #fff;
    font-size: 1rem;
    font-weight: 700;
    outline: none;
}
.pd-stock-info {
    color: rgba(255,255,255,0.35);
    font-size: 0.82rem;
}

/* Action Buttons */
.pd-actions {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
}
.pd-btn-cart, .pd-btn-buy {
    padding: 0.9rem 2rem;
    border-radius: 14px;
    font-size: 1rem;
    font-weight: 800;
    border: none;
    cursor: pointer;
    transition: transform 0.2s, box-shadow 0.2s;
    letter-spacing: 0.3px;
}
.pd-btn-cart {
    background: rgba(255,255,255,0.08);
    border: 1.5px solid rgba(255,255,255,0.2);
    color: #fff;
}
.pd-btn-cart:hover {
    background: rgba(255,255,255,0.16);
    transform: translateY(-2px);
}
.pd-btn-buy {
    background: linear-gradient(135deg, #7c3aed, #4f46e5);
    color: #fff;
    box-shadow: 0 8px 28px rgba(124,58,237,0.45);
}
.pd-btn-buy:hover {
    transform: translateY(-3px);
    box-shadow: 0 14px 36px rgba(124,58,237,0.6);
}

/* Out of stock */
.pd-out-msg {
    padding: 1.25rem 1.5rem;
    background: rgba(100,100,120,0.15);
    border: 1px solid rgba(255,255,255,0.1);
    border-radius: 14px;
    color: rgba(255,255,255,0.5);
    font-size: 1rem;
    margin-bottom: 1.5rem;
}

/* ── Thumbnail Navigation Strip ────────────────────────── */
.pd-thumb-strip {
    display: flex;
    gap: 1rem;
    margin-top: 2.5rem;
    padding-top: 1.5rem;
    border-top: 1px solid rgba(255,255,255,0.08);
    justify-content: space-between;
}
.pd-thumb-link {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    text-decoration: none;
    background: rgba(255,255,255,0.05);
    border: 1px solid rgba(255,255,255,0.1);
    border-radius: 14px;
    padding: 0.6rem 1rem 0.6rem 0.6rem;
    transition: background 0.2s, transform 0.2s;
    flex: 0 1 auto;
    max-width: 48%;
}
.pd-thumb-link:hover {
    background: rgba(255,255,255,0.1);
    transform: translateY(-2px);
}
.pd-thumb-img {
    width: 54px;
    height: 54px;
    object-fit: cover;
    border-radius: 9px;
    flex-shrink: 0;
}
.pd-thumb-label {
    color: #fff;
    font-size: 0.85rem;
    font-weight: 700;
    line-height: 1.3;
}

/* ── Responsive ────────────────────────────────────────── */
@media (max-width: 576px) {
    .pd-hero { height: 55vh; min-height: 320px; }
    .pd-hero-content { padding: 1rem 1rem 0; }
    .pd-panel { padding: 1.25rem; }
    .pd-nav-label { display: none; }
    .pd-nav-btn { padding: 0.5rem 0.75rem; }
    .pd-actions { flex-direction: column; }
    .pd-btn-cart, .pd-btn-buy { width: 100%; text-align: center; }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const minus = document.getElementById('qty-minus');
    const plus  = document.getElementById('qty-plus');
    const input = document.getElementById('qty-input');
    if (!minus || !plus || !input) return;

    const max = parseInt(input.max) || 99;
    minus.addEventListener('click', () => {
        const v = parseInt(input.value) || 1;
        if (v > 1) input.value = v - 1;
    });
    plus.addEventListener('click', () => {
        const v = parseInt(input.value) || 1;
        if (v < max) input.value = v + 1;
    });
});
</script>

@endsection
