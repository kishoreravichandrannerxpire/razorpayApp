<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Coupon;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    /**
     * Display the shopping cart.
     */
    public function index()
    {
        $cart = session()->get('cart', []);
        $subtotal = 0;

        foreach ($cart as $id => $item) {
            // Re-fetch product to ensure live price, name & discount
            $product = Product::find($id);
            $cart[$id]['name'] = $product ? $product->product_name : ($item['name'] ?? 'Product');
            $cart[$id]['description'] = $product ? $product->description : ($item['description'] ?? '');
            $itemPrice = $product ? $product->finalPrice() : $item['price'];
            $cart[$id]['price'] = $itemPrice;
            $cart[$id]['original_price'] = $product ? (float) $product->price : $item['price'];
            $cart[$id]['discount_percentage'] = $product ? (float) $product->discount_percentage : 0;
            $subtotal += $itemPrice * $item['quantity'];
        }

        // Save refreshed cart back to session
        session()->put('cart', $cart);

        // Check applied coupon validation against subtotal
        $couponSession = session()->get('coupon');
        $appliedCoupon = null;
        $discountAmount = 0;

        if ($couponSession) {
            $coupon = Coupon::where('code', $couponSession['code'])->first();
            if ($coupon) {
                $validation = $coupon->isValidForCart($subtotal);
                if ($validation['valid']) {
                    $appliedCoupon = $coupon;
                    $discountAmount = $coupon->calculateDiscount($subtotal);
                    session()->put('coupon.discount', $discountAmount);
                } else {
                    // Invalidated e.g. subtotal fell below min spend
                    session()->forget('coupon');
                    session()->flash('warning', 'Applied coupon was removed: ' . $validation['message']);
                }
            } else {
                session()->forget('coupon');
            }
        }

        $total = max(0, $subtotal - $discountAmount);

        // Fetch active coupons for cart banner / suggestions
        $availableCoupons = Coupon::where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('expires_at')->orWhere('expires_at', '>', now());
            })
            ->get();

        return view('cart.index', compact('cart', 'subtotal', 'discountAmount', 'total', 'appliedCoupon', 'availableCoupons'));
    }

    /**
     * Add a product to the cart.
     */
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => ['required', 'integer'],
            'quantity' => ['nullable', 'integer', 'min:1'],
        ]);

        $product = Product::find($request->product_id);
        if (! $product) {
            return back()->with('error', 'Product not found.');
        }

        $quantity = (int) $request->input('quantity', 1);

        $cart = session()->get('cart', []);
        $existingQty = $cart[$product->id]['quantity'] ?? 0;

        if (! $product->hasEnoughStock($existingQty + $quantity)) {
            return back()->with('error', "Only {$product->stock} unit(s) of '{$product->product_name}' available.");
        }

        $finalPrice = $product->finalPrice();

        if (isset($cart[$product->id])) {
            $cart[$product->id]['quantity'] += $quantity;
            $cart[$product->id]['price'] = $finalPrice;
        } else {
            $cart[$product->id] = [
                'id' => $product->id,
                'name' => $product->product_name,
                'price' => $finalPrice,
                'original_price' => (float) $product->price,
                'discount_percentage' => (float) $product->discount_percentage,
                'quantity' => $quantity,
                'description' => $product->description,
            ];
        }

        session()->put('cart', $cart);

        if (Auth::check()) {
            Cart::updateOrCreate(
                ['user_id' => Auth::id(), 'product_id' => $product->id],
                ['quantity' => $cart[$product->id]['quantity']]
            );
        }

        if ($request->input('action') === 'buy_now') {
            return redirect()->route('cart.index')->with('success', "'{$product->product_name}' added. Proceeding to cart.");
        }

        return back()->with('success', "'{$product->product_name}' added to cart.");
    }

    /**
     * Apply coupon code to cart.
     */
    public function applyCoupon(Request $request)
    {
        $request->validate([
            'coupon_code' => ['required', 'string', 'max:50'],
        ]);

        $code = strtoupper(trim($request->coupon_code));
        $coupon = Coupon::where('code', $code)->first();

        if (! $coupon) {
            return back()->with('error', "Invalid coupon code '{$code}'.");
        }

        // Calculate cart subtotal
        $cart = session()->get('cart', []);
        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }

        if ($subtotal <= 0) {
            return back()->with('error', 'Your cart is empty.');
        }

        $validation = $coupon->isValidForCart($subtotal);
        if (! $validation['valid']) {
            return back()->with('error', $validation['message']);
        }

        $discountAmount = $coupon->calculateDiscount($subtotal);

        session()->put('coupon', [
            'code' => $coupon->code,
            'type' => $coupon->type,
            'value' => $coupon->value,
            'discount' => $discountAmount,
        ]);

        return back()->with('success', "Coupon '{$coupon->code}' applied successfully! You saved ₹" . number_format($discountAmount, 2));
    }

    /**
     * Remove coupon code from cart.
     */
    public function removeCoupon()
    {
        session()->forget('coupon');
        return back()->with('success', 'Coupon code removed.');
    }

    /**
     * Update quantity of a product in the cart.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        $cart = session()->get('cart', []);

        if (!isset($cart[$id])) {
            return back()->with('error', 'Product not found in cart.');
        }

        $product = Product::find($id);
        if (!$product) {
            return back()->with('error', 'Product not found.');
        }

        $newQuantity = (int) $request->quantity;

        if (! $product->hasEnoughStock($newQuantity)) {
            return back()->with('error', "Only {$product->stock} unit(s) of '{$product->product_name}' available.");
        }

        $cart[$id]['quantity'] = $newQuantity;
        $cart[$id]['price'] = $product->finalPrice();
        session()->put('cart', $cart);

        if (Auth::check()) {
            Cart::updateOrCreate(
                ['user_id' => Auth::id(), 'product_id' => $id],
                ['quantity' => $newQuantity]
            );
        }

        return back()->with('success', "'{$product->product_name}' quantity updated to {$newQuantity}.");
    }

    /**
     * Remove a product from the cart.
     */
    public function remove($id)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);

            if (Auth::check()) {
                Cart::where('user_id', Auth::id())->where('product_id', $id)->delete();
            }

            return back()->with('success', "Item removed from cart.");
        }

        return back()->with('error', 'Product not found in cart.');
    }

    /**
     * Clear the entire cart.
     */
    public function clear()
    {
        session()->forget('cart');
        session()->forget('coupon');

        if (Auth::check()) {
            Cart::where('user_id', Auth::id())->delete();
        }

        return back()->with('success', 'Cart cleared.');
    }
}
