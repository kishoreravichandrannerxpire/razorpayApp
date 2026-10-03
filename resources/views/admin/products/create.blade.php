@extends('layouts.app')

@section('title', 'Admin — Create Product')

@section('content')

<div class="row justify-content-center">
    <div class="col-lg-7">

        <div class="d-flex align-items-center gap-3 mb-4">
            <a href="{{ route('admin.products.index') }}" class="btn btn-sm btn-outline-secondary">← Back</a>
            <div>
                <h1 class="fw-bold mb-0" style="color:#6f42c1; font-size:1.6rem;">➕ Create New Product</h1>
                <p class="text-muted mb-0" style="font-size:0.9rem;">Fill in the details below to add a product to the store.</p>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <form action="{{ route('admin.products.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="product_name" class="form-label fw-semibold">Product Name <span class="text-danger">*</span></label>
                        <input type="text" id="product_name" name="product_name"
                               class="form-control @error('product_name') is-invalid @enderror"
                               value="{{ old('product_name') }}"
                               placeholder="e.g. Wireless Mouse" required maxlength="150">
                        @error('product_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label fw-semibold">Description <span class="text-danger">*</span></label>
                        <textarea id="description" name="description" rows="4"
                                  class="form-control @error('description') is-invalid @enderror"
                                  placeholder="Detailed product description…" required>{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="image_url" class="form-label fw-semibold">Product Image URL <span class="text-muted small">(optional)</span></label>
                        <input type="url" id="image_url" name="image_url"
                               class="form-control @error('image_url') is-invalid @enderror"
                               value="{{ old('image_url') }}"
                               placeholder="https://example.com/product-image.jpg">
                        @error('image_url')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        @if(old('image_url'))
                            <div class="mt-2">
                                <img src="{{ old('image_url') }}" alt="Preview" class="img-thumbnail" style="max-height:120px;">
                            </div>
                        @endif
                        <small class="text-muted">Paste a direct link to the product image (JPEG, PNG, WebP, etc.).</small>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-sm-6">
                            <label for="price" class="form-label fw-semibold">Price (₹) <span class="text-danger">*</span></label>
                            <input type="number" id="price" name="price" step="0.01" min="0"
                                   class="form-control @error('price') is-invalid @enderror"
                                   value="{{ old('price') }}"
                                   placeholder="0.00" required>
                            @error('price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-sm-6">
                            <label for="stock" class="form-label fw-semibold">Stock Quantity <span class="text-danger">*</span></label>
                            <input type="number" id="stock" name="stock" min="0"
                                   class="form-control @error('stock') is-invalid @enderror"
                                   value="{{ old('stock', 0) }}"
                                   placeholder="0" required>
                            @error('stock')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="status" class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                        <select id="status" name="status"
                                class="form-select @error('status') is-invalid @enderror" required>
                            <option value="">— Select Status —</option>
                            <option value="Active"   {{ old('status') === 'Active'   ? 'selected' : '' }}>Active</option>
                            <option value="Inactive" {{ old('status') === 'Inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary px-4">Create Product</button>
                        <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary px-4">Cancel</a>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>
@endsection
