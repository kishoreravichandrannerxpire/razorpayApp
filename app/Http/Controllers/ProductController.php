<?php

namespace App\Http\Controllers;

use App\Models\Product;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::where('status', 'Active')->orderByDesc('id')->get();

        return view('products.index', compact('products'));
    }

    public function show(Product $product)
    {
        $next = Product::where('status', 'Active')
            ->where('id', '>', $product->id)
            ->orderBy('id', 'asc')
            ->first();

        $prev = Product::where('status', 'Active')
            ->where('id', '<', $product->id)
            ->orderBy('id', 'desc')
            ->first();

        return view('products.show', compact('product', 'next', 'prev'));
    }

}
