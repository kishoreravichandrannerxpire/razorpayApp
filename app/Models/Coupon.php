<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'type',
        'value',
        'min_cart_amount',
        'max_discount_amount',
        'usage_limit',
        'used_count',
        'expires_at',
        'is_active',
    ];

    protected $casts = [
        'value' => 'float',
        'min_cart_amount' => 'float',
        'max_discount_amount' => 'float',
        'usage_limit' => 'integer',
        'used_count' => 'integer',
        'expires_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    /**
     * Check if coupon is valid for a given cart subtotal.
     */
    public function isValidForCart(float $subtotal): array
    {
        if (! $this->is_active) {
            return ['valid' => false, 'message' => 'This coupon is no longer active.'];
        }

        if ($this->expires_at && $this->expires_at->isPast()) {
            return ['valid' => false, 'message' => 'This coupon has expired.'];
        }

        if ($this->usage_limit !== null && $this->used_count >= $this->usage_limit) {
            return ['valid' => false, 'message' => 'This coupon usage limit has been reached.'];
        }

        if ($this->min_cart_amount !== null && $subtotal < $this->min_cart_amount) {
            return [
                'valid' => false,
                'message' => 'Minimum cart amount of ₹' . number_format($this->min_cart_amount, 2) . ' required to use this coupon.'
            ];
        }

        return ['valid' => true, 'message' => 'Coupon applied successfully!'];
    }

    /**
     * Calculate discount for a given subtotal.
     */
    public function calculateDiscount(float $subtotal): float
    {
        if ($this->type === 'percent') {
            $discount = ($subtotal * $this->value) / 100;
            if ($this->max_discount_amount !== null && $this->max_discount_amount > 0) {
                $discount = min($discount, $this->max_discount_amount);
            }
        } else {
            // Fixed discount
            $discount = $this->value;
        }

        return min($discount, $subtotal);
    }
}
