<?php

namespace Database\Seeders;

use App\Models\Coupon;
use Illuminate\Database\Seeder;

class CouponSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $coupons = [
            [
                'code' => 'WELCOME20',
                'type' => 'percent',
                'value' => 20.00,
                'min_cart_amount' => 500.00,
                'max_discount_amount' => 1000.00,
                'usage_limit' => 100,
                'is_active' => true,
            ],
            [
                'code' => 'SAVE100',
                'type' => 'fixed',
                'value' => 100.00,
                'min_cart_amount' => 500.00,
                'max_discount_amount' => null,
                'usage_limit' => 500,
                'is_active' => true,
            ],
            [
                'code' => 'SUPER50',
                'type' => 'percent',
                'value' => 50.00,
                'min_cart_amount' => 1000.00,
                'max_discount_amount' => 2500.00,
                'usage_limit' => 50,
                'is_active' => true,
            ],
            [
                'code' => 'FLAT500',
                'type' => 'fixed',
                'value' => 500.00,
                'min_cart_amount' => 3000.00,
                'max_discount_amount' => null,
                'usage_limit' => 200,
                'is_active' => true,
            ],
        ];

        foreach ($coupons as $coupon) {
            Coupon::updateOrCreate(['code' => $coupon['code']], $coupon);
        }
    }
}
