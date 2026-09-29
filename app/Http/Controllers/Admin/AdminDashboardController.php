<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;

class AdminDashboardController extends Controller
{
    /**
     * Show the admin dashboard with summary stats.
     */
    public function index()
    {
        $totalOrders        = Order::count();
        $totalRevenue       = Order::where('status', 'Paid')->sum('total_amount');
        $outOfStockProducts = Product::where('stock', '<=', 0)->orderBy('product_name')->get();
        $lowStockProducts   = Product::where('stock', '>', 0)
                                     ->where('stock', '<', 5)
                                     ->orderBy('stock')
                                     ->get();

        return view('admin.dashboard', compact(
            'totalOrders',
            'totalRevenue',
            'outOfStockProducts',
            'lowStockProducts'
        ));
    }
}
