<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'order_number',
        'total_amount',
        'currency',
        'coupon_code',
        'discount_amount',
        'status',
        'razorpay_order_id',
    ];

    protected $casts = [
        'total_amount' => 'float',
        'discount_amount' => 'float',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'order_items')
            ->withPivot('quantity', 'price', 'subtotal')
            ->withTimestamps();
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class)->latestOfMany();
    }

    public function markFailedAndRestoreStock(): void
{
    if (in_array($this->status, ['Failed', 'Cancelled'])) {
        return; // already released, don't double-restore
    }

    \Illuminate\Support\Facades\DB::transaction(function () {
        foreach ($this->orderItems as $item) {
            Product::whereKey($item->product_id)->lockForUpdate()->increment('stock', $item->quantity);
        }
        // Give the coupon use back, since this order will not complete
        if ($this->coupon_code) {
            Coupon::where('code', $this->coupon_code)
                ->where('used_count', '>', 0)
                ->decrement('used_count');
        }

        $this->update(['status' => 'Failed']);
    });
}
}