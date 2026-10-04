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
}
