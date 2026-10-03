@extends('layouts.app')

@section('title', 'Create Coupon — Admin')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
            <h2 class="h3 fw-bold mb-0 text-white">Create New Coupon</h2>
            <a href="{{ route('admin.coupons.index') }}" class="btn btn-outline-secondary btn-sm">
                &larr; Back to Coupons
            </a>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <form action="{{ route('admin.coupons.store') }}" method="POST">
                    @csrf

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="code" class="form-label">Coupon Code <span class="text-danger">*</span></label>
                            <input 
                                type="text" 
                                id="code" 
                                name="code" 
                                class="form-control font-monospace text-uppercase @error('code') is-invalid @enderror" 
                                value="{{ old('code') }}" 
                                placeholder="e.g. SAVE20" 
                                required
                            >
                            @error('code')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6">
                            <label for="type" class="form-label">Discount Type <span class="text-danger">*</span></label>
                            <select id="type" name="type" class="form-select @error('type') is-invalid @enderror" required>
                                <option value="percent" {{ old('type') === 'percent' ? 'selected' : '' }}>Percentage (%)</option>
                                <option value="fixed" {{ old('type') === 'fixed' ? 'selected' : '' }}>Fixed Amount (₹)</option>
                            </select>
                            @error('type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6">
                            <label for="value" class="form-label">Discount Value <span class="text-danger">*</span></label>
                            <input 
                                type="number" 
                                step="0.01" 
                                id="value" 
                                name="value" 
                                class="form-control @error('value') is-invalid @enderror" 
                                value="{{ old('value') }}" 
                                placeholder="e.g. 20 for 20% or 500 for ₹500" 
                                required
                            >
                            @error('value')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6">
                            <label for="min_cart_amount" class="form-label">Minimum Cart Amount (₹)</label>
                            <input 
                                type="number" 
                                step="0.01" 
                                id="min_cart_amount" 
                                name="min_cart_amount" 
                                class="form-control @error('min_cart_amount') is-invalid @enderror" 
                                value="{{ old('min_cart_amount') }}" 
                                placeholder="e.g. 1000 (Optional)"
                            >
                            @error('min_cart_amount')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6">
                            <label for="max_discount_amount" class="form-label">Maximum Discount Limit (₹)</label>
                            <input 
                                type="number" 
                                step="0.01" 
                                id="max_discount_amount" 
                                name="max_discount_amount" 
                                class="form-control @error('max_discount_amount') is-invalid @enderror" 
                                value="{{ old('max_discount_amount') }}" 
                                placeholder="Cap for % discounts e.g. 500 (Optional)"
                            >
                            @error('max_discount_amount')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6">
                            <label for="usage_limit" class="form-label">Usage Limit (Times)</label>
                            <input 
                                type="number" 
                                id="usage_limit" 
                                name="usage_limit" 
                                class="form-control @error('usage_limit') is-invalid @enderror" 
                                value="{{ old('usage_limit') }}" 
                                placeholder="e.g. 100 (Optional)"
                            >
                            @error('usage_limit')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6">
                            <label for="expires_at" class="form-label">Expiration Date & Time</label>
                            <input 
                                type="datetime-local" 
                                id="expires_at" 
                                name="expires_at" 
                                class="form-control @error('expires_at') is-invalid @enderror" 
                                value="{{ old('expires_at') }}"
                            >
                            @error('expires_at')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6 d-flex align-items-center mt-4">
                            <div class="form-check form-switch mt-2">
                                <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', 1) ? 'checked' : '' }}>
                                <label class="form-check-label fw-bold" for="is_active">Is Coupon Active?</label>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 pt-3 border-top d-flex justify-content-end gap-2">
                        <a href="{{ route('admin.coupons.index') }}" class="btn btn-outline-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary px-4">Create Coupon</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
