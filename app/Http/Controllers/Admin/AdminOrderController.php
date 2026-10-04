<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class AdminOrderController extends Controller
{
    /** Allowed statuses an admin can set on an order. */
    private const STATUSES = [
        'Pending', 'Paid', 'Failed', 'Cancelled', 'Shipped', 'Refunded',
    ];

    /**
     * List all orders with their user and payment summary.
     */
    public function index()
    {
        $orders = Order::with('user')
                       ->orderByDesc('id')
                       ->paginate(25);

        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Show the status-edit form for a specific order.
     */
    public function edit(Order $order)
    {
        $statuses = self::STATUSES;
        return view('admin.orders.edit', compact('order', 'statuses'));
    }

    /**
     * Update the order's status.
     */
    public function update(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:' . implode(',', self::STATUSES),
        ]);

        $newStatus = $validated['status'];
        $oldStatus = $order->status;

        // Maatram illa na onnum pannaadhu
        if ($newStatus === $oldStatus) {
            return redirect()->route('admin.orders.index');
        }

        // Failed/Cancelled order-oda stock already release aagiduchu.
        // Thirumba Paid/Pending aakkina stock double-a count aagum, so block pannurom.
        if (in_array($oldStatus, ['Failed', 'Cancelled'], true)) {
            return redirect()->route('admin.orders.index')
                             ->with('error', "Order #{$order->order_number} is already {$oldStatus} and its stock was released. Customer pudhu order podanum.");
        }

        // Stock hold pannura status-la irundhu Failed/Cancelled-ku pona stock thirumba kudukkanum
        if (in_array($newStatus, ['Failed', 'Cancelled'], true)
            && in_array($oldStatus, Order::STOCK_HOLDING, true)) {
            $order->markFailedAndRestoreStock($newStatus);
        } else {
            $order->update(['status' => $newStatus]);
        }

        return redirect()->route('admin.orders.index')
                         ->with('success', "Order #{$order->order_number} status updated to {$newStatus}.");
    }
}