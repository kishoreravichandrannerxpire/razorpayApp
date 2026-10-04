<?php

namespace App\Http\Controllers;

use Razorpay\Api\Api;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Razorpay\Api\Errors\SignatureVerificationError;

class PaymentController extends Controller
{
    /**
     * Create order for all items in the cart and initialize Razorpay order.
     */
   public function createOrder(Request $request)
{
    $cart = session()->get('cart', []);

    if (empty($cart)) {
        return redirect()->route('products.index')
            ->with('error', 'Your cart is empty. Please add products before checking out.');
    }

    try {
        $couponSession = session()->get('coupon');

        $order = \Illuminate\Support\Facades\DB::transaction(function () use ($cart, $couponSession) {

            $order = Order::create([
                'user_id' => auth()->id(),
                'order_number' => 'ORD-' . strtoupper(uniqid()),
                'total_amount' => 0,
                'currency' => 'INR',
                'status' => 'Pending',
            ]);

            $subtotal = 0;

            foreach ($cart as $item) {
                $product = Product::whereKey($item['id'])->lockForUpdate()->first();

                if (! $product || ! $product->hasEnoughStock($item['quantity'])) {
                    throw new \RuntimeException("'{$item['name']}' no longer has enough stock.");
                }

                $itemPrice = $product->finalPrice();
                $itemSubtotal = $itemPrice * $item['quantity'];

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'price' => $itemPrice,
                    'subtotal' => $itemSubtotal,
                ]);

                $product->decrement('stock', $item['quantity']);

                $subtotal += $itemSubtotal;
            }

            $discountAmount = 0;
            $couponCode = null;

            if ($couponSession) {
                $coupon = \App\Models\Coupon::where('code', $couponSession['code'])->first();
                if ($coupon) {
                    $validation = $coupon->isValidForCart($subtotal);
                    if ($validation['valid']) {
                        $discountAmount = $coupon->calculateDiscount($subtotal);
                        $couponCode = $coupon->code;
                        $coupon->increment('used_count');
                    }
                }
            }

            $finalTotal = max(0, $subtotal - $discountAmount);

            $order->update([
                'total_amount' => $finalTotal,
                'coupon_code' => $couponCode,
                'discount_amount' => $discountAmount,
            ]);

            return $order;
        });
    } catch (\RuntimeException $e) {
        return redirect()->route('cart.index')->with('error', $e->getMessage());
    }

    // Create the Razorpay order BEFORE clearing the cart.
    // If this fails, release the stock/coupon we reserved and keep the cart intact.
    try {
        $api = new Api(
            config('services.razorpay.key'),
            config('services.razorpay.secret')
        );

        $razorpayOrder = $api->order->create([
            'receipt' => 'ORD_' . $order->id,
            'amount' => (int) round($order->total_amount * 100),
            'currency' => 'INR',
        ]);

        $order->update([
            'razorpay_order_id' => $razorpayOrder['id'],
        ]);
    } catch (\Throwable $e) {
        report($e);

        $order->markFailedAndRestoreStock();

        return redirect()->route('cart.index')
            ->with('error', 'We could not start the payment right now. Your cart is safe, please try again in a moment.');
    }

    // Payment order created successfully, now it is safe to clear the cart & coupon
    session()->forget('cart');
    session()->forget('coupon');
    \App\Models\Cart::where('user_id', auth()->id())->delete();

    $order->load('orderItems.product', 'user');

    return view('payment.checkout', compact('order', 'razorpayOrder'));
}

    /**
     * Verify Razorpay payment signature and mark order as Paid.
     */
   public function verifyPayment(Request $request)
{
    if (! $request->filled(['razorpay_order_id', 'razorpay_payment_id', 'razorpay_signature'])) {
        return redirect()->route('payment.failed');
    }

    // Ownership check: logged-in user-oda order mattum
    $order = Order::where('razorpay_order_id', $request->razorpay_order_id)
        ->where('user_id', auth()->id())
        ->first();

    if (! $order) {
        return redirect()->route('payment.failed');
    }

    try {
        $api = new Api(config('services.razorpay.key'), config('services.razorpay.secret'));
        $api->utility->verifyPaymentSignature([
            'razorpay_order_id'   => $request->razorpay_order_id,
            'razorpay_payment_id' => $request->razorpay_payment_id,
            'razorpay_signature'  => $request->razorpay_signature,
        ]);
    } catch (SignatureVerificationError $e) {
        report($e);
        return redirect()->route('payment.failed');
    }

    DB::transaction(function () use ($order, $request) {
        $order = Order::whereKey($order->id)->lockForUpdate()->first();

        Payment::updateOrCreate(
            ['razorpay_payment_id' => $request->razorpay_payment_id],
            [
                'order_id'          => $order->id,
                'razorpay_order_id' => $request->razorpay_order_id,
                'razorpay_signature'=> $request->razorpay_signature,
                'status'            => 'Success',
                'amount'            => $order->total_amount,
                'paid_at'           => now(),
            ]
        );

        if ($order->status === 'Pending') {
            $order->update(['status' => 'Paid']);
        } elseif (! in_array($order->status, ['Paid', 'Shipped', 'Refunded'], true)) {
            report(new \RuntimeException(
                "Payment {$request->razorpay_payment_id} received for {$order->order_number} but order is {$order->status}. Needs manual review or refund."
            ));
        }
    });

    session()->forget('cart');
    \App\Models\Cart::where('user_id', $order->user_id)->delete();

    return redirect()->route('payment.success');
}

    public function success()
    {
        return view('payment.success');
    }

    public function failed()
    {
        return view('payment.failed');
    }
}