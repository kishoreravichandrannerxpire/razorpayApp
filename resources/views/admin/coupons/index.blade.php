@extends('layouts.app')

@section('title', 'Manage Coupons — Admin')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 pb-2 border-bottom">
    <div>
        <h2 class="h3 fw-bold mb-1 text-white">Coupon Management</h2>
        <p class="text-muted mb-0">Create, view, toggle, and manage discount promo coupons</p>
    </div>
    <div>
        <a href="{{ route('admin.coupons.create') }}" class="btn btn-primary d-flex align-items-center gap-1">
            <span>+ Create New Coupon</span>
        </a>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th scope="col" class="ps-4 py-3">Code</th>
                        <th scope="col" class="py-3">Type</th>
                        <th scope="col" class="py-3 text-center">Value</th>
                        <th scope="col" class="py-3 text-center">Min Cart</th>
                        <th scope="col" class="py-3 text-center">Max Discount</th>
                        <th scope="col" class="py-3 text-center">Usage</th>
                        <th scope="col" class="py-3 text-center">Status</th>
                        <th scope="col" class="pe-4 py-3 text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($coupons as $coupon)
                        <tr>
                            <td class="ps-4 py-3">
                                <span class="fw-bold font-monospace text-primary fs-6">{{ $coupon->code }}</span>
                                @if($coupon->expires_at)
                                    <small class="d-block text-muted" style="font-size: 0.75rem;">
                                        Expires: {{ $coupon->expires_at->format('d M Y, h:i A') }}
                                    </small>
                                @endif
                            </td>
                            <td>
                                @if($coupon->type === 'percent')
                                    <span class="badge bg-info text-white fw-bold">% Off</span>
                                @else
                                    <span class="badge bg-warning text-dark fw-bold">Flat ₹</span>
                                @endif
                            </td>
                            <td class="text-center fw-bold text-white">
                                {{ $coupon->type === 'percent' ? $coupon->value . '%' : '₹' . number_format($coupon->value, 2) }}
                            </td>
                            <td class="text-center text-muted">
                                {{ $coupon->min_cart_amount ? '₹' . number_format($coupon->min_cart_amount, 2) : '—' }}
                            </td>
                            <td class="text-center text-muted">
                                {{ $coupon->max_discount_amount ? '₹' . number_format($coupon->max_discount_amount, 2) : '—' }}
                            </td>
                            <td class="text-center">
                                <span class="badge bg-secondary text-white fw-semibold" title="Used {{ $coupon->used_count }} of {{ $coupon->usage_limit ?? 'unlimited' }}">
                                    {{ $coupon->used_count }} / {{ $coupon->usage_limit ?? '∞' }}
                                </span>
                            </td>
                            <td class="text-center">
                                <form action="{{ route('admin.coupons.toggle', $coupon->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @if($coupon->is_active)
                                        <button type="submit" class="btn btn-sm btn-success py-0 px-2" style="font-size: 0.75rem;" title="Click to Deactivate">
                                            Active
                                        </button>
                                    @else
                                        <button type="submit" class="btn btn-sm btn-secondary py-0 px-2" style="font-size: 0.75rem;" title="Click to Activate">
                                            Inactive
                                        </button>
                                    @endif
                                </form>
                            </td>
                            <td class="pe-4 text-end">
                                <div class="d-flex justify-content-end gap-1">
                                    <a href="{{ route('admin.coupons.edit', $coupon->id) }}" class="btn btn-outline-info btn-sm py-1 px-2">
                                        Edit
                                    </a>
                                    <form action="{{ route('admin.coupons.destroy', $coupon->id) }}" method="POST" onsubmit="return confirm('Delete coupon {{ $coupon->code }}?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm py-1 px-2">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">
                                No coupons found. Create your first coupon above!
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($coupons->hasPages())
        <div class="card-footer py-3">
            {{ $coupons->links() }}
        </div>
    @endif
</div>
@endsection
