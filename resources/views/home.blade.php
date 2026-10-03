@extends('layouts.app')

@section('title', 'Razorpay Store — Premium Tech, Instant Payments')

@section('content')

{{-- ═══════════════════════════════════════════════════
     HERO  ─ Full-bleed cinematic dark header
═══════════════════════════════════════════════════ --}}
<section class="h-hero" id="hero">
    {{-- Animated grid background --}}
    <div class="h-grid-overlay"></div>
    {{-- Glowing orbs --}}
    <div class="h-orb h-orb-1"></div>
    <div class="h-orb h-orb-2"></div>
    <div class="h-orb h-orb-3"></div>

    <div class="h-hero-inner">
        {{-- Pill badge --}}
        <div class="h-pill" id="hero-pill">
            <span class="h-pill-dot"></span>
            <span>Powered by Razorpay · Instant &amp; Secure</span>
        </div>

        <h1 class="h-headline">
            Tech you <span class="h-word-swap">
                <span class="h-word active">love.</span>
                <span class="h-word">want.</span>
                <span class="h-word">need.</span>
            </span><br>
            <span class="h-headline-accent">Pay in seconds.</span>
        </h1>

        <p class="h-subline">
            From laptops to routers — browse top tech products<br class="d-none d-md-inline">
            and check out instantly with any payment method.
        </p>

        <div class="h-cta-row">
            <a href="{{ route('products.index') }}" class="h-btn-primary" id="hero-shop-btn">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
                Shop All Products
            </a>
            @guest
            <a href="{{ route('register') }}" class="h-btn-ghost" id="hero-register-btn">
                Create Free Account →
            </a>
            @endguest
        </div>

        {{-- Trust indicators --}}
        <div class="h-trust-row">
            <div class="h-trust-item">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#34d399" stroke-width="2.5"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                Bank-grade SSL
            </div>
            <div class="h-trust-sep"></div>
            <div class="h-trust-item">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#34d399" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                {{ \App\Models\Product::where('status','Active')->count() }}+ Products
            </div>
            <div class="h-trust-sep"></div>
            <div class="h-trust-item">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#34d399" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                Instant Checkout
            </div>
        </div>
    </div>

    {{-- Floating product preview cards --}}
    <div class="h-float-cards" aria-hidden="true">
        <div class="h-float-card h-fc-1">
            <img src="https://images.unsplash.com/photo-1496181133206-80ce9b88a853?w=200&q=80&fit=crop" alt="Laptop">
            <span>Laptop</span>
        </div>
        <div class="h-float-card h-fc-2">
            <img src="https://images.unsplash.com/photo-1527864550417-7fd91fc51a46?w=200&q=80&fit=crop" alt="Mouse">
            <span>Mouse</span>
        </div>
        <div class="h-float-card h-fc-3">
            <img src="https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=200&q=80&fit=crop" alt="Headset">
            <span>Headset</span>
        </div>
    </div>
</section>

{{-- ═══════════════════════════════════════════════════
     MARQUEE TICKER
═══════════════════════════════════════════════════ --}}
<div class="h-ticker-wrap" aria-hidden="true">
    <div class="h-ticker-track">
        @foreach(['Laptop','Mouse','Keyboard','Monitor','Headset','Printer','Pendrive','SSD','Webcam','Router','Laptop','Mouse','Keyboard','Monitor','Headset','Printer','Pendrive','SSD','Webcam','Router'] as $item)
            <span class="h-ticker-item">{{ $item }}</span>
            <span class="h-ticker-sep">·</span>
        @endforeach
    </div>
</div>

{{-- ═══════════════════════════════════════════════════
     FEATURED PRODUCTS  ─ Mosaic grid
═══════════════════════════════════════════════════ --}}
@if($featuredProducts->count() > 0)
<section class="h-section" id="featured">
    <div class="h-section-header">
        <div class="h-eyebrow">✦ Featured Picks</div>
        <h2 class="h-section-title">Handpicked <span class="h-accent">For You</span></h2>
        <p class="h-section-sub">Top-rated products, live stock — always up to date</p>
    </div>

    <div class="h-product-grid">
        @foreach($featuredProducts as $i => $product)
        <div class="h-pcard {{ '' }}">
            <div class="h-pcard-img-wrap">
                <img
                    src="{{ $product->displayImage() }}"
                    alt="{{ $product->product_name }}"
                    class="h-pcard-img"
                    loading="lazy"
                >
                <div class="h-pcard-overlay">
                    <form action="{{ route('cart.add') }}" method="POST" style="display:contents;">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <input type="hidden" name="quantity"   value="1">
                        <button type="submit" name="action" value="buy_now" class="h-pcard-quick-buy"
                                id="fp-buy-{{ $product->id }}">
                            ⚡ Quick Buy
                        </button>
                    </form>
                </div>
                @if($product->hasDiscount())
                    <div class="h-pcard-hot" style="background: linear-gradient(135deg, #dc2626, #ef4444);">
                        🏷️ {{ $product->discount_percentage }}% OFF
                    </div>
                @elseif($product->stock > 0 && $product->stock < 5)
                    <div class="h-pcard-hot">🔥 Hot</div>
                @endif
            </div>
            <div class="h-pcard-info">
                <div class="h-pcard-meta">
                    <h3 class="h-pcard-name">{{ $product->product_name }}</h3>
                    <div class="text-end">
                        <span class="h-pcard-price">₹{{ number_format($product->finalPrice(), 2) }}</span>
                        @if($product->hasDiscount())
                            <small class="d-block text-muted text-decoration-line-through" style="font-size: 0.75rem;">
                                ₹{{ number_format($product->price, 2) }}
                            </small>
                        @endif
                    </div>
                </div>
                <p class="h-pcard-desc">{{ Str::limit($product->description ?? 'Premium quality tech.', 60) }}</p>
                <form action="{{ route('cart.add') }}" method="POST" class="h-pcard-form">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <input type="hidden" name="quantity"   value="1">
                    <button type="submit" name="action" value="add" class="h-pcard-btn-cart"
                            id="fp-cart-{{ $product->id }}">
                        🛒 Add to Cart
                    </button>
                    <button type="submit" name="action" value="buy_now" class="h-pcard-btn-buy"
                            id="fp-buynow-{{ $product->id }}">
                        Buy Now
                    </button>
                </form>
            </div>
        </div>
        @endforeach
    </div>

    <div class="h-section-footer">
        <a href="{{ route('products.index') }}" class="h-btn-outline" id="home-viewall-btn">
            View Full Catalog
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
        </a>
    </div>
</section>
@endif

{{-- ═══════════════════════════════════════════════════
     CATEGORIES  ─ Visual icon grid
═══════════════════════════════════════════════════ --}}
<section class="h-section" id="categories">
    <div class="h-section-header">
        <div class="h-eyebrow">📦 Browse by Type</div>
        <h2 class="h-section-title">Shop by <span class="h-accent">Category</span></h2>
    </div>

    <div class="h-cat-grid">
        @php
        $cats = [
            ['icon' => 'https://images.unsplash.com/photo-1496181133206-80ce9b88a853?w=160&q=80&fit=crop', 'label' => 'Laptops',    'color' => '#3b82f6'],
            ['icon' => 'https://images.unsplash.com/photo-1527864550417-7fd91fc51a46?w=160&q=80&fit=crop', 'label' => 'Peripherals','color' => '#8b5cf6'],
            ['icon' => 'https://images.unsplash.com/photo-1527443224154-c4a573d81be4?w=160&q=80&fit=crop', 'label' => 'Monitors',   'color' => '#06b6d4'],
            ['icon' => 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=160&q=80&fit=crop', 'label' => 'Audio',      'color' => '#ec4899'],
            ['icon' => 'https://images.unsplash.com/photo-1597852074816-d933c7d2b988?w=160&q=80&fit=crop', 'label' => 'Storage',    'color' => '#f59e0b'],
            ['icon' => 'https://images.unsplash.com/photo-1606904825846-647eb07f5be2?w=160&q=80&fit=crop', 'label' => 'Networking', 'color' => '#10b981'],
        ];
        @endphp

        @foreach($cats as $cat)
        <a href="{{ route('products.index') }}" class="h-cat-card" style="--cat-color: {{ $cat['color'] }};">
            <div class="h-cat-img-wrap">
                <img src="{{ $cat['icon'] }}" alt="{{ $cat['label'] }}" class="h-cat-img" loading="lazy">
                <div class="h-cat-glow"></div>
            </div>
            <span class="h-cat-label">{{ $cat['label'] }}</span>
        </a>
        @endforeach
    </div>
</section>

{{-- ═══════════════════════════════════════════════════
     WHY US  ─ Horizontal feature band
═══════════════════════════════════════════════════ --}}
<section class="h-why-band" id="why">
    <div class="h-why-item">
        <div class="h-why-icon">🔐</div>
        <div>
            <div class="h-why-title">Bank-grade Security</div>
            <div class="h-why-sub">256-bit SSL encryption on every transaction</div>
        </div>
    </div>
    <div class="h-why-div"></div>
    <div class="h-why-item">
        <div class="h-why-icon">⚡</div>
        <div>
            <div class="h-why-title">Instant Checkout</div>
            <div class="h-why-sub">UPI, cards, netbanking — one tap done</div>
        </div>
    </div>
    <div class="h-why-div"></div>
    <div class="h-why-item">
        <div class="h-why-icon">📦</div>
        <div>
            <div class="h-why-title">Live Stock Tracking</div>
            <div class="h-why-sub">Real-time inventory — never oversell</div>
        </div>
    </div>
    <div class="h-why-div"></div>
    <div class="h-why-item">
        <div class="h-why-icon">🎯</div>
        <div>
            <div class="h-why-title">Premium Products</div>
            <div class="h-why-sub">Curated top-tier tech, quality guaranteed</div>
        </div>
    </div>
</section>

{{-- ═══════════════════════════════════════════════════
     HOW IT WORKS  ─ Stepper layout
═══════════════════════════════════════════════════ --}}
<section class="h-section" id="how">
    <div class="h-section-header">
        <div class="h-eyebrow">🗺️ Three Steps</div>
        <h2 class="h-section-title">Shop in <span class="h-accent">Minutes</span></h2>
        <p class="h-section-sub">No apps. No friction. Just pick, pay, done.</p>
    </div>

    <div class="h-steps">
        <div class="h-step">
            <div class="h-step-num">01</div>
            <div class="h-step-icon">🔍</div>
            <h4 class="h-step-title">Browse</h4>
            <p class="h-step-desc">Explore our full catalog with smart search and live stock status on every card.</p>
        </div>
        <div class="h-step-arrow">→</div>
        <div class="h-step">
            <div class="h-step-num">02</div>
            <div class="h-step-icon">🛒</div>
            <h4 class="h-step-title">Cart or Buy</h4>
            <p class="h-step-desc">Add to cart for later, or hit Buy Now for direct single-click checkout.</p>
        </div>
        <div class="h-step-arrow">→</div>
        <div class="h-step">
            <div class="h-step-num">03</div>
            <div class="h-step-icon">💳</div>
            <h4 class="h-step-title">Pay Securely</h4>
            <p class="h-step-desc">Razorpay handles your payment securely — UPI, debit/credit, netbanking.</p>
        </div>
    </div>
</section>

{{-- ═══════════════════════════════════════════════════
     CTA  ─ Glassmorphism banner
═══════════════════════════════════════════════════ --}}
<section class="h-cta" id="cta">
    <div class="h-cta-bg-blob h-cta-blob-1"></div>
    <div class="h-cta-bg-blob h-cta-blob-2"></div>
    <div class="h-cta-content">
        <div class="h-cta-badge">🎉 Limited Stock Available</div>
        <h2 class="h-cta-title">Don't miss out on<br>top tech deals</h2>
        <p class="h-cta-sub">Secure, fast, and effortless — start shopping today.</p>
        <div class="h-cta-btns">
            <a href="{{ route('products.index') }}" class="h-cta-btn-primary" id="cta-browse-btn">Browse Products</a>
            @guest
            <a href="{{ route('register') }}" class="h-cta-btn-sec" id="cta-signup-btn">Sign Up Free</a>
            @else
            <a href="{{ route('cart.index') }}" class="h-cta-btn-sec" id="cta-cart-btn">View Cart</a>
            @endguest
        </div>
    </div>
</section>

{{-- ═══════════════════════════════════════════════════
     STYLES
═══════════════════════════════════════════════════ --}}
<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800;900&display=swap');

/* ── Reset scope ─────────────────────────────────── */
#hero, #featured, #categories, #why, #how, #cta,
.h-ticker-wrap, .h-why-band {
    font-family: 'Inter', system-ui, sans-serif;
}

/* ═══════ HERO ═════════════════════════════════════ */
.h-hero {
    position: relative;
    min-height: 560px;
    background: #060d1a;
    border-radius: 28px;
    overflow: hidden;
    display: flex;
    align-items: center;
    padding: 4rem 2rem 4rem;
    margin-bottom: 0;
    margin-top: -1rem;
}

/* Animated grid */
.h-grid-overlay {
    position: absolute;
    inset: 0;
    background-image:
        linear-gradient(rgba(59,130,246,0.07) 1px, transparent 1px),
        linear-gradient(90deg, rgba(59,130,246,0.07) 1px, transparent 1px);
    background-size: 48px 48px;
    animation: gridShift 20s linear infinite;
}
@keyframes gridShift {
    from { background-position: 0 0; }
    to   { background-position: 48px 48px; }
}

/* Glowing orbs */
.h-orb {
    position: absolute;
    border-radius: 50%;
    filter: blur(90px);
    pointer-events: none;
}
.h-orb-1 { width:500px; height:500px; background:#1d4ed8; opacity:0.18; top:-200px; left:-150px; animation: pulse 8s ease-in-out infinite alternate; }
.h-orb-2 { width:350px; height:350px; background:#7c3aed; opacity:0.15; bottom:-120px; right:-80px; animation: pulse 10s 2s ease-in-out infinite alternate; }
.h-orb-3 { width:220px; height:220px; background:#06b6d4; opacity:0.12; top:50%; left:40%; animation: pulse 6s 1s ease-in-out infinite alternate; }
@keyframes pulse {
    from { transform: scale(1); opacity: 0.15; }
    to   { transform: scale(1.15); opacity: 0.25; }
}

.h-hero-inner {
    position: relative;
    z-index: 3;
    max-width: 640px;
}

/* Pill */
.h-pill {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    background: rgba(255,255,255,0.07);
    border: 1px solid rgba(255,255,255,0.14);
    color: #93c5fd;
    border-radius: 50px;
    padding: 0.4rem 1.1rem;
    font-size: 0.82rem;
    font-weight: 600;
    margin-bottom: 1.5rem;
    backdrop-filter: blur(8px);
}
.h-pill-dot {
    width: 8px; height: 8px;
    background: #34d399;
    border-radius: 50%;
    box-shadow: 0 0 8px #34d399;
    animation: blink 1.8s ease-in-out infinite;
}
@keyframes blink { 0%,100%{ opacity:1; } 50%{ opacity:0.3; } }

/* Headline */
.h-headline {
    font-size: clamp(2.4rem, 5.5vw, 4.2rem);
    font-weight: 900;
    line-height: 1.1;
    color: #fff;
    letter-spacing: -2px;
    margin-bottom: 1.2rem;
}
.h-word-swap {
    position: relative;
    display: inline-block;
    min-width: 6rem;
    color: #60a5fa;
}
.h-word { position: absolute; left: 0; opacity: 0; transition: opacity 0.5s ease; white-space: nowrap; }
.h-word.active { opacity: 1; position: relative; }
.h-headline-accent {
    background: linear-gradient(90deg, #60a5fa, #a78bfa, #38bdf8);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.h-subline {
    font-size: 1.08rem;
    color: rgba(255,255,255,0.65);
    line-height: 1.75;
    margin-bottom: 2.2rem;
}

/* CTA row */
.h-cta-row { display: flex; gap: 1rem; flex-wrap: wrap; margin-bottom: 2.4rem; }
.h-btn-primary {
    display: inline-flex;
    align-items: center;
    gap: 0.55rem;
    background: linear-gradient(135deg, #3b82f6, #6366f1);
    color: #fff;
    font-weight: 700;
    font-size: 0.97rem;
    padding: 0.85rem 2rem;
    border-radius: 50px;
    text-decoration: none;
    box-shadow: 0 8px 28px rgba(99,102,241,0.45);
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}
.h-btn-primary:hover { transform: translateY(-3px); box-shadow: 0 14px 36px rgba(99,102,241,0.55); color:#fff; }
.h-btn-ghost {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    border: 1.5px solid rgba(255,255,255,0.25);
    color: rgba(255,255,255,0.82);
    font-weight: 600;
    font-size: 0.97rem;
    padding: 0.85rem 2rem;
    border-radius: 50px;
    text-decoration: none;
    backdrop-filter: blur(8px);
    transition: background 0.2s, color 0.2s;
}
.h-btn-ghost:hover { background: rgba(255,255,255,0.1); color:#fff; }

/* Trust row */
.h-trust-row { display: flex; align-items: center; gap: 1.2rem; flex-wrap: wrap; }
.h-trust-item { display: flex; align-items: center; gap: 0.4rem; font-size: 0.82rem; color: rgba(255,255,255,0.55); font-weight: 600; }
.h-trust-sep { width:1px; height:14px; background: rgba(255,255,255,0.2); }

/* Floating cards */
.h-float-cards {
    position: absolute;
    right: 4%;
    top: 50%;
    transform: translateY(-50%);
    display: flex;
    flex-direction: column;
    gap: 1rem;
    z-index: 3;
}
@media (max-width: 900px) { .h-float-cards { display: none; } }
.h-float-card {
    background: rgba(255,255,255,0.06);
    border: 1px solid rgba(255,255,255,0.13);
    border-radius: 16px;
    overflow: hidden;
    width: 130px;
    backdrop-filter: blur(12px);
    box-shadow: 0 8px 24px rgba(0,0,0,0.3);
    transition: transform 0.3s ease;
}
.h-float-card img { width:100%; height:80px; object-fit:cover; }
.h-float-card span {
    display:block;
    font-size: 0.75rem;
    font-weight: 700;
    color: rgba(255,255,255,0.75);
    padding: 0.4rem 0.7rem;
    text-align: center;
}
.h-fc-1 { animation: floatA 5s ease-in-out infinite; }
.h-fc-2 { animation: floatB 6s 1s ease-in-out infinite; }
.h-fc-3 { animation: floatA 4.5s 2s ease-in-out infinite; }
@keyframes floatA { 0%,100%{ transform: translateY(0); } 50%{ transform: translateY(-10px); } }
@keyframes floatB { 0%,100%{ transform: translateY(0); } 50%{ transform: translateY(10px); } }

/* ═══════ TICKER ══════════════════════════════════ */
.h-ticker-wrap {
    background: linear-gradient(135deg, #1e3a5f, #2d5086);
    overflow: hidden;
    padding: 0.9rem 0;
    margin: 0 -12px 0;
}
.h-ticker-track {
    display: flex;
    white-space: nowrap;
    animation: ticker 22s linear infinite;
    will-change: transform;
}
@keyframes ticker { from { transform: translateX(0); } to { transform: translateX(-50%); } }
.h-ticker-item {
    font-size: 0.85rem;
    font-weight: 700;
    color: rgba(255,255,255,0.75);
    text-transform: uppercase;
    letter-spacing: 1px;
    padding: 0 1.2rem;
}
.h-ticker-sep { color: rgba(255,255,255,0.3); font-weight: 400; }

/* ═══════ SECTION COMMON ══════════════════════════ */
.h-section { padding: 4.5rem 0 2rem; }
.h-section-header { text-align: center; margin-bottom: 3rem; }
.h-eyebrow {
    display: inline-block;
    background: linear-gradient(135deg, #ede9fe, #dbeafe);
    color: #4f46e5;
    border-radius: 50px;
    padding: 0.3rem 1rem;
    font-size: 0.8rem;
    font-weight: 700;
    letter-spacing: 0.4px;
    margin-bottom: 0.8rem;
}
.h-section-title {
    font-size: clamp(1.8rem, 4vw, 2.8rem);
    font-weight: 900;
    color: #0f172a;
    letter-spacing: -1px;
    margin-bottom: 0.5rem;
}
.h-accent {
    background: linear-gradient(135deg, #3b82f6, #6366f1);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}
.h-section-sub { color: #64748b; font-size: 1rem; margin: 0; }
.h-section-footer { text-align: center; margin-top: 2.5rem; }

/* ═══════ PRODUCT GRID ════════════════════════════ */
.h-product-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1.25rem;
}
@media (max-width: 900px) { .h-product-grid { grid-template-columns: repeat(2, 1fr); } }
@media (max-width: 600px) { .h-product-grid { grid-template-columns: 1fr; } }

/* First card no longer spans 2 rows — all cards are equal */
.h-pcard--featured { grid-row: span 1; }

.h-pcard {
    background: #fff;
    border: 1px solid #e5e7eb;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 4px 16px rgba(0,0,0,0.06);
    transition: transform 0.28s cubic-bezier(.22,.68,0,1.4), box-shadow 0.28s ease;
    display: flex;
    flex-direction: column;
}
.h-pcard:hover { transform: translateY(-8px) scale(1.01); box-shadow: 0 24px 56px rgba(0,0,0,0.14); }

.h-pcard-img-wrap {
    position: relative;
    overflow: hidden;
    background: #f8fafc;
}
.h-pcard--featured .h-pcard-img-wrap { height: 180px; }
.h-pcard:not(.h-pcard--featured) .h-pcard-img-wrap { height: 180px; }

.h-pcard-img { width:100%; height:100%; object-fit:cover; transition: transform 0.4s ease; }
.h-pcard:hover .h-pcard-img { transform: scale(1.09); }

/* Overlay */
.h-pcard-overlay {
    position: absolute;
    inset: 0;
    background: rgba(15,23,42,0.55);
    backdrop-filter: blur(2px);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.25s ease;
}
.h-pcard:hover .h-pcard-overlay { opacity: 1; }
.h-pcard-quick-buy {
    background: #fff;
    color: #1e3a5f;
    font-weight: 800;
    font-size: 0.88rem;
    padding: 0.65rem 1.5rem;
    border: none;
    border-radius: 50px;
    cursor: pointer;
    box-shadow: 0 4px 16px rgba(0,0,0,0.25);
    transition: transform 0.2s ease;
}
.h-pcard-quick-buy:hover { transform: scale(1.06); }

/* Hot badge */
.h-pcard-hot {
    position: absolute;
    top: 10px;
    left: 10px;
    background: linear-gradient(135deg, #f59e0b, #ef4444);
    color: #fff;
    font-size: 0.72rem;
    font-weight: 800;
    padding: 0.25rem 0.7rem;
    border-radius: 50px;
}

.h-pcard-info { padding: 1.1rem 1.2rem 1.3rem; display:flex; flex-direction:column; flex:1; }
.h-pcard-meta { display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:0.4rem; }
.h-pcard-name { font-size:1rem; font-weight:800; color:#0f172a; margin:0; }
.h-pcard-price { font-size:1.1rem; font-weight:900; color:#059669; white-space:nowrap; }
.h-pcard-desc { font-size:0.8rem; color:#64748b; line-height:1.5; flex-grow:1; margin-bottom:0.9rem; }

.h-pcard-form { display:flex; gap:0.5rem; }
.h-pcard-btn-cart {
    flex:1;
    background: transparent;
    border: 1.5px solid #e2e8f0;
    color: #374151;
    font-weight:700;
    font-size:0.78rem;
    padding:0.5rem;
    border-radius:10px;
    cursor:pointer;
    transition: all 0.2s;
}
.h-pcard-btn-cart:hover { border-color:#3b82f6; color:#3b82f6; background:#eff6ff; }
.h-pcard-btn-buy {
    flex:1;
    background: linear-gradient(135deg,#3b82f6,#6366f1);
    border: none;
    color:#fff;
    font-weight:700;
    font-size:0.78rem;
    padding:0.5rem;
    border-radius:10px;
    cursor:pointer;
    transition:opacity 0.2s;
    box-shadow: 0 3px 10px rgba(99,102,241,0.3);
}
.h-pcard-btn-buy:hover { opacity:0.88; }

/* View all btn */
.h-btn-outline {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    background: transparent;
    border: 2px solid #3b82f6;
    color: #3b82f6;
    font-weight: 700;
    font-size: 0.95rem;
    padding: 0.75rem 2.2rem;
    border-radius: 50px;
    text-decoration: none;
    transition: all 0.22s ease;
}
.h-btn-outline:hover { background:#3b82f6; color:#fff; }

/* ═══════ CATEGORY GRID ═══════════════════════════ */
.h-cat-grid {
    display: grid;
    grid-template-columns: repeat(6, 1fr);
    gap: 1rem;
}
@media (max-width:900px){ .h-cat-grid { grid-template-columns: repeat(3,1fr); } }
@media (max-width:500px){ .h-cat-grid { grid-template-columns: repeat(2,1fr); } }

.h-cat-card {
    border-radius: 18px;
    overflow: hidden;
    background: #fff;
    border: 1px solid #e5e7eb;
    text-decoration: none;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0;
    box-shadow: 0 4px 16px rgba(0,0,0,0.05);
    transition: transform 0.25s cubic-bezier(.22,.68,0,1.4), box-shadow 0.25s ease;
}
.h-cat-card:hover { transform: translateY(-6px) scale(1.03); box-shadow: 0 16px 40px rgba(0,0,0,0.12); }
.h-cat-img-wrap { position:relative; width:100%; height:90px; overflow:hidden; }
.h-cat-img { width:100%; height:100%; object-fit:cover; transition:transform 0.35s ease; }
.h-cat-card:hover .h-cat-img { transform: scale(1.1); }
.h-cat-glow {
    position: absolute;
    inset: 0;
    background: linear-gradient(to bottom, transparent 40%, var(--cat-color) 200%);
    opacity: 0.35;
}
.h-cat-label {
    font-size: 0.8rem;
    font-weight: 800;
    color: #0f172a;
    padding: 0.6rem 0.5rem;
    text-align: center;
}

/* ═══════ WHY BAND ════════════════════════════════ */
.h-why-band {
    display: flex;
    align-items: center;
    gap: 0;
    background: linear-gradient(135deg, #060d1a 0%, #0f2040 100%);
    border-radius: 22px;
    padding: 2.5rem 3rem;
    margin: 0 0 3rem;
    flex-wrap: wrap;
    row-gap: 1.5rem;
}
.h-why-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    flex: 1;
    min-width: 180px;
}
.h-why-icon { font-size: 2rem; line-height: 1; }
.h-why-title { font-size: 0.95rem; font-weight: 800; color: #fff; margin-bottom: 0.15rem; }
.h-why-sub { font-size: 0.78rem; color: rgba(255,255,255,0.5); line-height: 1.4; }
.h-why-div { width:1px; height:48px; background:rgba(255,255,255,0.1); margin: 0 1.5rem; }
@media(max-width:700px){ .h-why-div{ display:none; } }

/* ═══════ STEPS ═══════════════════════════════════ */
.h-steps {
    display: flex;
    align-items: flex-start;
    justify-content: center;
    gap: 1.5rem;
    flex-wrap: wrap;
}
.h-step {
    flex: 1;
    min-width: 200px;
    max-width: 280px;
    background: #fff;
    border: 1px solid #e5e7eb;
    border-radius: 24px;
    padding: 2.5rem 1.8rem 2rem;
    text-align: center;
    position: relative;
    box-shadow: 0 4px 20px rgba(0,0,0,0.05);
    transition: transform 0.25s ease, box-shadow 0.25s ease;
}
.h-step:hover { transform: translateY(-6px); box-shadow: 0 16px 40px rgba(0,0,0,0.1); }
.h-step-num {
    position: absolute;
    top: -16px;
    left: 50%;
    transform: translateX(-50%);
    background: linear-gradient(135deg,#3b82f6,#6366f1);
    color: #fff;
    font-size: 0.78rem;
    font-weight: 900;
    padding: 0.3rem 0.75rem;
    border-radius: 50px;
    letter-spacing: 0.5px;
    box-shadow: 0 4px 12px rgba(99,102,241,0.4);
}
.h-step-icon { font-size: 2.8rem; margin-bottom: 0.8rem; }
.h-step-title { font-size: 1.05rem; font-weight: 800; color: #0f172a; margin-bottom: 0.5rem; }
.h-step-desc { font-size: 0.83rem; color: #64748b; line-height: 1.6; margin: 0; }
.h-step-arrow { font-size: 1.8rem; color: #cbd5e1; margin-top: 3rem; }
@media(max-width:700px){ .h-step-arrow{ display:none; } }

/* ═══════ CTA BANNER ══════════════════════════════ */
.h-cta {
    position: relative;
    background: linear-gradient(135deg, #0f172a 0%, #1e3a5f 50%, #312e81 100%);
    border-radius: 28px;
    padding: 5rem 2rem;
    text-align: center;
    overflow: hidden;
    margin-bottom: 2rem;
}
.h-cta-bg-blob {
    position: absolute;
    border-radius: 50%;
    filter: blur(80px);
    pointer-events: none;
}
.h-cta-blob-1 { width:400px;height:400px;background:#3b82f6;opacity:0.15;top:-120px;right:-80px; }
.h-cta-blob-2 { width:300px;height:300px;background:#7c3aed;opacity:0.2;bottom:-80px;left:-60px; }
.h-cta-content { position:relative; z-index:2; }
.h-cta-badge {
    display: inline-block;
    background: rgba(251,191,36,0.18);
    border: 1px solid rgba(251,191,36,0.3);
    color: #fbbf24;
    border-radius: 50px;
    padding: 0.35rem 1.1rem;
    font-size: 0.82rem;
    font-weight: 700;
    margin-bottom: 1.2rem;
}
.h-cta-title {
    font-size: clamp(1.8rem, 4vw, 3rem);
    font-weight: 900;
    color: #fff;
    letter-spacing: -1.5px;
    line-height: 1.15;
    margin-bottom: 0.85rem;
}
.h-cta-sub { color: rgba(255,255,255,0.62); font-size:1rem; margin-bottom:2rem; }
.h-cta-btns { display:flex; gap:1rem; justify-content:center; flex-wrap:wrap; }
.h-cta-btn-primary {
    display: inline-block;
    background: #fff;
    color: #0f172a;
    font-weight: 800;
    padding: 0.85rem 2.4rem;
    border-radius: 50px;
    text-decoration: none;
    font-size: 0.95rem;
    box-shadow: 0 6px 20px rgba(0,0,0,0.2);
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}
.h-cta-btn-primary:hover { transform:translateY(-3px); box-shadow:0 12px 32px rgba(0,0,0,0.28); color:#0f172a; }
.h-cta-btn-sec {
    display: inline-block;
    border: 1.5px solid rgba(255,255,255,0.3);
    color: rgba(255,255,255,0.88);
    font-weight: 700;
    padding: 0.85rem 2.4rem;
    border-radius: 50px;
    text-decoration: none;
    font-size: 0.95rem;
    backdrop-filter: blur(8px);
    transition: background 0.2s;
}
.h-cta-btn-sec:hover { background: rgba(255,255,255,0.12); color:#fff; }

/* ═══════ WORD SWAP JS ════════════════════════════ */
</style>

<script>
// Rotating word animation (no libs)
(function() {
    var words = document.querySelectorAll('.h-word');
    if (!words.length) return;
    var idx = 0;
    setInterval(function() {
        words[idx].classList.remove('active');
        idx = (idx + 1) % words.length;
        words[idx].classList.add('active');
    }, 2200);
})();
</script>

@endsection
