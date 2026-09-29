<?php

namespace App\Http\Controllers;

use App\Models\Cart;
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
        $total = 0;

        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        return view('cart.index', compact('cart', 'total'));
    }

    /**
     * Add a product to the cart.
     * Decrements the product's stock in database immediately upon reservation.
     * Synchronizes with database carts table if user is logged in.
     */
    public function add(Request $request)
{
    $request->validate([
        'product_id' => ['required', 'exists:products,id'],
        'quantity' => ['nullable', 'integer', 'min:1'],
    ]);

    $product = Product::findOrFail($request->product_id);
    $quantity = (int) $request->input('quantity', 1);

    $cart = session()->get('cart', []);
    $existingQty = $cart[$product->id]['quantity'] ?? 0;

    if (! $product->hasEnoughStock($existingQty + $quantity)) {
        return back()->with('error', "Only {$product->stock} unit(s) of '{$product->product_name}' available.");
    }

    if (isset($cart[$product->id])) {
        $cart[$product->id]['quantity'] += $quantity;
    } else {
        $cart[$product->id] = [
            'id' => $product->id,
            'name' => $product->product_name,
            'price' => (float) $product->price,
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

    return back()->with('success', "'{$product->product_name}' added to cart.");
}

    /**
     * Update quantity of a product in the cart.
     * Adjusts the product's DB stock according to quantity difference.
     * Synchronizes with database carts table if user is logged in.
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

    // Stock check mattum — DB touch pannாdhu
    if (! $product->hasEnoughStock($newQuantity)) {
        return back()->with('error', "Only {$product->stock} unit(s) of '{$product->product_name}' available.");
    }

    $cart[$id]['quantity'] = $newQuantity;
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
     * Restores the reserved quantity back to product stock in database.
     * Synchronizes with database carts table if user is logged in.
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
     * Restores all reserved items back to product stock in database.
     * Synchronizes with database carts table if user is logged in.
     */
    public function clear()
{
    session()->forget('cart');

    if (Auth::check()) {
        Cart::where('user_id', Auth::id())->delete();
    }

    return back()->with('success', 'Cart cleared.');
}
}
