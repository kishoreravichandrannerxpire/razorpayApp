@extends('layouts.app')

@section('title', 'Admin — Products')

@section('content')

<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h1 class="fw-bold mb-1" style="color:#6f42c1;">📦 Product Management</h1>
        <p class="text-muted mb-0">Create, edit, and delete products from your store.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.dashboard') }}" class="btn btn-sm btn-outline-secondary">← Dashboard</a>
        <a href="{{ route('admin.products.create') }}" class="btn btn-sm btn-primary">+ New Product</a>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Price</th>
                        <th>Stock</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                    <tr>
                        <td class="text-muted" style="font-size:0.85rem;">{{ $product->id }}</td>
                        <td class="fw-semibold">{{ $product->product_name }}</td>
                        <td>₹{{ number_format($product->price, 2) }}</td>
                        <td>
                            @if($product->stock <= 0)
                                <span class="badge bg-danger px-2 py-1 fw-bold">
                                    0 — OUT OF STOCK
                                </span>
                            @elseif($product->stock < 5)
                                <span class="badge bg-warning text-dark px-2 py-1">
                                    {{ $product->stock }} (Low)
                                </span>
                            @else
                                <span class="badge bg-success px-2 py-1">
                                    {{ $product->stock }}
                                </span>
                            @endif
                        </td>
                        <td>
                            <span class="badge {{ $product->status === 'Active' ? 'bg-success' : 'bg-secondary' }}">
                                {{ $product->status }}
                            </span>
                        </td>
                        <td class="text-muted" style="font-size:0.82rem;">{{ $product->created_at->format('d M Y') }}</td>
                        <td class="text-end">
                            <a href="{{ route('admin.products.edit', $product) }}"
                               class="btn btn-sm btn-outline-primary me-1">Edit</a>

                            {{-- Delete via POST form (no JS) --}}
                            <form action="{{ route('admin.products.destroy', $product) }}"
                                  method="POST" class="d-inline"
                                  onsubmit="return confirm('Delete this product?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">No products found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($products->hasPages())
    <div class="card-footer bg-white border-top-0 d-flex justify-content-end">
        {{ $products->links() }}
    </div>
    @endif
</div>
@endsection
