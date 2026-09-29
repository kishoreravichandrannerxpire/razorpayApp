<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class AdminProductController extends Controller
{
    /**
     * List all products.
     */
    public function index()
    {
        $products = Product::orderByDesc('id')->paginate(20);
        return view('admin.products.index', compact('products'));
    }

    /**
     * Show the create product form.
     */
    public function create()
    {
        return view('admin.products.create');
    }

    /**
     * Store a new product.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_name' => 'required|string|max:150',
            'description'  => 'required|string',
            'price'        => 'required|numeric|min:0',
            'stock'        => 'required|integer|min:0',
            'status'       => 'required|in:Active,Inactive',
        ]);

        Product::create($validated);

        return redirect()->route('admin.products.index')
                         ->with('success', 'Product created successfully.');
    }

    /**
     * Show the edit product form.
     */
    public function edit(Product $product)
    {
        return view('admin.products.edit', compact('product'));
    }

    /**
     * Update an existing product.
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'product_name' => 'required|string|max:150',
            'description'  => 'required|string',
            'price'        => 'required|numeric|min:0',
            'stock'        => 'required|integer|min:0',
            'status'       => 'required|in:Active,Inactive',
        ]);

        $product->update($validated);

        return redirect()->route('admin.products.index')
                         ->with('success', 'Product updated successfully.');
    }

    /**
     * Delete a product.
     */
    public function destroy(Product $product)
{
    try {
        $product->delete();
    } catch (\Illuminate\Database\QueryException $e) {
        return back()->with('error', 'Cannot delete this product — it has existing orders. Consider marking it Inactive instead.');
    }

    return redirect()->route('admin.products.index')
                     ->with('success', 'Product deleted.');
}
}
