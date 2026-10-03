<?php

namespace App\Http\Controllers;

use App\Models\Product;

class HomeController extends Controller
{
    /**
     * Show the public landing / home page.
     */
    public function index()
    {
        // Show up to 6 featured active products on the home page
        $featuredProducts = Product::where('status', 'Active')
            ->where('stock', '>', 0)
            ->latest()
            ->take(6)
            ->get();

        return view('home', compact('featuredProducts'));
    }
}
