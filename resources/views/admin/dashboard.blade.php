@extends('layouts.app')

@section('title', 'Admin Dashboard — Razorpay Store')

@section('content')
<div class="row mb-4">
    <div class="col-12 d-flex align-items-center justify-content-between">
        <div>
            <h1 class="fw-bold mb-1" style="color:#6f42c1;">🛡 Admin Dashboard</h1>
            <p class="text-muted mb-0">Overview of your store's performance</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.products.index') }}" class="btn btn-sm btn-outline-primary">Products</a>
            <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-outline-secondary">Orders</a>
        </div>
    </div>
</div>

{{-- ── Urgent Out of Stock Alert ────────────────────────────────────────── --}}
@if($outOfStockProducts->isNotEmpty())
<div class="card border-0 shadow-sm mb-4" style="border-left: 6px solid #dc3545 !important;">
    <div class="card-header bg-danger text-white fw-bold d-flex align-items-center justify-content-between py-3">
        <div class="d-flex align-items-center gap-2">
            <span style="font-size:1.4rem;">🚨</span>
            <span class="fs-6">CRITICAL: {{ $outOfStockProducts->count() }} Product(s) are Completely Out of Stock!</span>
        </div>
        <span class="badge bg-white text-danger fw-bold px-3 py-2">Customers Cannot Buy</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Product Name</th>
                        <th>Price</th>
                        <th>Current Stock</th>
                        <th>Storefront Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($outOfStockProducts as $product)
                    <tr>
                        <td class="text-muted" style="font-size:0.85rem;">{{ $product->id }}</td>
                        <td class="fw-bold text-dark">{{ $product->product_name }}</td>
                        <td>₹{{ number_format($product->price, 2) }}</td>
                        <td>
                            <span class="badge bg-danger px-3 py-2">
                                0 Left (EMPTY)
                            </span>
                        </td>
                        <td>
                            <span class="badge bg-secondary">
                                🚫 Purchase Blocked
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-sm btn-danger fw-semibold">
                                ⚡ Restock Product
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif

{{-- ── Stats Cards ─────────────────────────────────────────────────────────── --}}
<div class="row g-4 mb-5">

    {{-- Total Orders --}}
    <div class="col-md-3">
        <div class="card h-100 border-0 shadow-sm" style="border-left: 5px solid #0d6efd !important;">
            <div class="card-body d-flex align-items-center gap-3">
                <div style="font-size:2.2rem;">📦</div>
                <div>
                    <p class="text-muted mb-1 fw-semibold" style="font-size:0.8rem; letter-spacing:.05em;">TOTAL ORDERS</p>
                    <h2 class="fw-bold mb-0" style="font-size:1.8rem; color:#0d6efd;">{{ $totalOrders }}</h2>
                </div>
            </div>
        </div>
    </div>

    {{-- Total Revenue --}}
    <div class="col-md-3">
        <div class="card h-100 border-0 shadow-sm" style="border-left: 5px solid #198754 !important;">
            <div class="card-body d-flex align-items-center gap-3">
                <div style="font-size:2.2rem;">💰</div>
                <div>
                    <p class="text-muted mb-1 fw-semibold" style="font-size:0.8rem; letter-spacing:.05em;">TOTAL REVENUE</p>
                    <h2 class="fw-bold mb-0" style="font-size:1.8rem; color:#198754;">
                        ₹{{ number_format($totalRevenue, 2) }}
                    </h2>
                </div>
            </div>
        </div>
    </div>

    {{-- Out of Stock Count --}}
    <div class="col-md-3">
        <div class="card h-100 border-0 shadow-sm" style="border-left: 5px solid #dc3545 !important;">
            <div class="card-body d-flex align-items-center gap-3">
                <div style="font-size:2.2rem;">🚫</div>
                <div>
                    <p class="text-muted mb-1 fw-semibold" style="font-size:0.8rem; letter-spacing:.05em;">OUT OF STOCK (0)</p>
                    <h2 class="fw-bold mb-0" style="font-size:1.8rem; color:#dc3545;">{{ $outOfStockProducts->count() }}</h2>
                </div>
            </div>
        </div>
    </div>

    {{-- Low Stock Count --}}
    <div class="col-md-3">
        <div class="card h-100 border-0 shadow-sm" style="border-left: 5px solid #ffc107 !important;">
            <div class="card-body d-flex align-items-center gap-3">
                <div style="font-size:2.2rem;">⚠️</div>
                <div>
                    <p class="text-muted mb-1 fw-semibold" style="font-size:0.8rem; letter-spacing:.05em;">LOW STOCK (&lt; 5)</p>
                    <h2 class="fw-bold mb-0" style="font-size:1.8rem; color:#d39e00;">{{ $lowStockProducts->count() }}</h2>
                </div>
            </div>
        </div>
    </div>

</div>

{{-- ── Low Stock Alert Table ──────────────────────────────────────────────── --}}
@if($lowStockProducts->isNotEmpty())
<div class="card border-0 shadow-sm">
    <div class="card-header fw-bold" style="background:linear-gradient(90deg,#fff3cd,#fff); border-bottom:1px solid #ffe08a;">
        ⚠️ Low Stock Alert (Needs Restock Soon) <span class="badge bg-warning text-dark ms-2">{{ $lowStockProducts->count() }} item(s)</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Product Name</th>
                        <th>Status</th>
                        <th>Stock Remaining</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($lowStockProducts as $product)
                    <tr>
                        <td class="text-muted" style="font-size:0.85rem;">{{ $product->id }}</td>
                        <td class="fw-semibold">{{ $product->product_name }}</td>
                        <td>
                            <span class="badge {{ $product->status === 'Active' ? 'bg-success' : 'bg-secondary' }}">
                                {{ $product->status }}
                            </span>
                        </td>
                        <td>
                            <span class="badge {{ $product->stock === 0 ? 'bg-danger' : 'bg-warning text-dark' }} px-3 py-2">
                                {{ $product->stock }} left
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-sm btn-outline-primary">Edit Stock</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@else
<div class="alert alert-success shadow-sm">
    ✅ All products have sufficient stock (≥ 5 units).
</div>
@endif
@endsection
