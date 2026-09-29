@extends('layouts.app')

@section('title', 'Admin — Orders')

@section('content')

<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h1 class="fw-bold mb-1" style="color:#6f42c1;">🧾 Order Management</h1>
        <p class="text-muted mb-0">View all customer orders and update their status.</p>
    </div>
    <a href="{{ route('admin.dashboard') }}" class="btn btn-sm btn-outline-secondary">← Dashboard</a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Order No.</th>
                        <th>Customer</th>
                        <th>Total</th>
                        <th>Currency</th>
                        <th>Status</th>
                        <th>Placed On</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                    <tr>
                        <td class="text-muted" style="font-size:0.82rem;">{{ $order->id }}</td>
                        <td class="fw-semibold" style="font-family:monospace;">{{ $order->order_number }}</td>
                        <td>
                            @if($order->user)
                                <span class="fw-semibold">{{ $order->user->name }}</span><br>
                                <small class="text-muted">{{ $order->user->email }}</small>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td class="fw-bold">₹{{ number_format($order->total_amount, 2) }}</td>
                        <td class="text-muted">{{ $order->currency }}</td>
                        <td>
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
                        </td>
                        <td class="text-muted" style="font-size:0.82rem;">{{ $order->created_at->format('d M Y, H:i') }}</td>
                        <td class="text-end">
                            <a href="{{ route('admin.orders.edit', $order) }}"
                               class="btn btn-sm btn-outline-primary">Update Status</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-4">No orders found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($orders->hasPages())
    <div class="card-footer bg-white border-top-0 d-flex justify-content-end pt-3">
        {{ $orders->links() }}
    </div>
    @endif
</div>
@endsection
