<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;

class AdminCouponController extends Controller
{
    /**
     * Display a listing of coupons.
     */
    public function index()
    {
        $coupons = Coupon::orderByDesc('id')->paginate(15);
        return view('admin.coupons.index', compact('coupons'));
    }

    /**
     * Show form to create a new coupon.
     */
    public function create()
    {
        return view('admin.coupons.create');
    }

    /**
     * Store a newly created coupon in database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code'                => 'required|string|max:50|unique:coupons,code',
            'type'                => 'required|in:percent,fixed',
            'value'               => 'required|numeric|min:0.01',
            'min_cart_amount'     => 'nullable|numeric|min:0',
            'max_discount_amount' => 'nullable|numeric|min:0',
            'usage_limit'         => 'nullable|integer|min:1',
            'expires_at'          => 'nullable|date',
            'is_active'           => 'boolean',
        ]);

        $validated['code'] = strtoupper(trim($validated['code']));
        $validated['is_active'] = $request->has('is_active');

        Coupon::create($validated);

        return redirect()->route('admin.coupons.index')
                         ->with('success', 'Coupon created successfully.');
    }

    /**
     * Show form to edit an existing coupon.
     */
    public function edit(Coupon $coupon)
    {
        return view('admin.coupons.edit', compact('coupon'));
    }

    /**
     * Update an existing coupon.
     */
    public function update(Request $request, Coupon $coupon)
    {
        $validated = $request->validate([
            'code'                => 'required|string|max:50|unique:coupons,code,' . $coupon->id,
            'type'                => 'required|in:percent,fixed',
            'value'               => 'required|numeric|min:0.01',
            'min_cart_amount'     => 'nullable|numeric|min:0',
            'max_discount_amount' => 'nullable|numeric|min:0',
            'usage_limit'         => 'nullable|integer|min:1',
            'expires_at'          => 'nullable|date',
            'is_active'           => 'boolean',
        ]);

        $validated['code'] = strtoupper(trim($validated['code']));
        $validated['is_active'] = $request->has('is_active');

        $coupon->update($validated);

        return redirect()->route('admin.coupons.index')
                         ->with('success', 'Coupon updated successfully.');
    }

    /**
     * Toggle coupon active status.
     */
    public function toggle(Coupon $coupon)
    {
        $coupon->update(['is_active' => ! $coupon->is_active]);

        $status = $coupon->is_active ? 'activated' : 'deactivated';
        return back()->with('success', "Coupon {$coupon->code} {$status}.");
    }

    /**
     * Remove the coupon.
     */
    public function destroy(Coupon $coupon)
    {
        $coupon->delete();

        return redirect()->route('admin.coupons.index')
                         ->with('success', 'Coupon deleted successfully.');
    }
}
