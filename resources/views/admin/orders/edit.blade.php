@extends('layouts.app')

@section('title', 'Admin — Update Order Status')

@section('content')

<div class="row justify-content-center">
    <div class="col-lg-6">

        <div class="d-flex align-items-center gap-3 mb-4">
            <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-outline-secondary">← Back to Orders</a>
            <div>
                <h1 class="fw-bold mb-0" style="color:#6f42c1; font-size:1.6rem;">🔄 Update Order Status</h1>
                <p class="text-muted mb-0" style="font-size:0.9rem;">
                    Order <strong style="font-family:monospace;">{{ $order->order_number }}</strong>
                </p>
            </div>
        </div>

        {{-- Order Summary Card --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header fw-semibold bg-light">Order Summary</div>
            <div class="card-body">
                <dl class="row mb-0" style="font-size:0.92rem;">
                    <dt class="col-sm-4 text-muted">Customer</dt>
                    <dd class="col-sm-8">{{ $order->user->name ?? '—' }} <small class="text-muted">({{ $order->user->email ?? '' }})</small></dd>

                    <dt class="col-sm-4 text-muted">Total Amount</dt>
                    <dd class="col-sm-8 fw-bold">₹{{ number_format($order->total_amount, 2) }} {{ $order->currency }}</dd>

                    <dt class="col-sm-4 text-muted">Razorpay ID</dt>
                    <dd class="col-sm-8" style="font-family:monospace; font-size:0.82rem;">{{ $order->razorpay_order_id ?? '—' }}</dd>

                    <dt class="col-sm-4 text-muted">Placed On</dt>
                    <dd class="col-sm-8">{{ $order->created_at->format('d M Y, H:i') }}</dd>

                    <dt class="col-sm-4 text-muted">Current Status</dt>
                    <dd class="col-sm-8">
                        @php
                            $statusColors = [
                                'Pending'   => 'warning text-dark',
                                'Paid'      => 'success',
                                'Failed'    => 'danger',
                                'Cancelled' => 'secondary',
                                'Shipped'   => 'info text-dark',
                                'Refunded'  => 'dark',
                            ];
                            $color = $statusColors[$order->status] ?? 'secondary';
                        @endphp
                        <span class="badge bg-{{ $color }} px-2 py-1">{{ $order->status }}</span>
                    </dd>
                </dl>
            </div>
        </div>

        {{-- Status Update Form --}}
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <form action="{{ route('admin.orders.update', $order) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label for="status" class="form-label fw-semibold">New Status <span class="text-danger">*</span></label>
                        <select id="status" name="status"
                                class="form-select @error('status') is-invalid @enderror" required>
                            @foreach($statuses as $s)
                                <option value="{{ $s }}" {{ old('status', $order->status) === $s ? 'selected' : '' }}>
                                    {{ $s }}
                                </option>
                            @endforeach
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary px-4">Update Status</button>
                        <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary px-4">Cancel</a>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>
@endsection
