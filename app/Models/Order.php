<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Order extends Model
{
    // Indha status-la irundha stock hold aagi irukkum
    public const STOCK_HOLDING = ['Pending', 'Paid', 'Shipped'];

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

    public function markFailedAndRestoreStock(string $newStatus = 'Failed'): void
    {
        DB::transaction(function () use ($newStatus) {
            // Fresh row + lock: webhook-um admin-um ore time-la vandhaalum rendu thadava stock thirumba varaadhu
            $order = self::whereKey($this->id)->lockForUpdate()->first();

            // Stock hold panna status illana (already Failed/Cancelled/Refunded) onnum pannaadhu
            if (! $order || ! in_array($order->status, self::STOCK_HOLDING, true)) {
                return;
            }

            foreach ($order->orderItems as $item) {
                // withTrashed: admin delete pannina product-kum stock thirumba sera
                Product::withTrashed()
                    ->whereKey($item->product_id)
                    ->lockForUpdate()
                    ->increment('stock', $item->quantity);
            }

            // Order complete aagaadhu, so coupon use-a thirumba kudukkurom
            if ($order->coupon_code) {
                Coupon::where('code', $order->coupon_code)
                    ->where('used_count', '>', 0)
                    ->decrement('used_count');
            }

            $order->update(['status' => $newStatus]);
        });

        // In-memory model-um latest status-ku sync aaga
        $this->refresh();
    }
}