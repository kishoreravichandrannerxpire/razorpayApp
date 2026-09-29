<?php

namespace Database\Seeders;

use App\Models\Order;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $orders = [
            ['id' => 1, 'user_id' => 1, 'order_number' => 'ORD001', 'total_amount' => 55800, 'currency' => 'INR', 'status' => 'Paid', 'razorpay_order_id' => 'order_A001'],
            ['id' => 2, 'user_id' => 2, 'order_number' => 'ORD002', 'total_amount' => 2500, 'currency' => 'INR', 'status' => 'Pending', 'razorpay_order_id' => 'order_A002'],
            ['id' => 3, 'user_id' => 3, 'order_number' => 'ORD003', 'total_amount' => 9000, 'currency' => 'INR', 'status' => 'Paid', 'razorpay_order_id' => 'order_A003'],
            ['id' => 4, 'user_id' => 1, 'order_number' => 'ORD004', 'total_amount' => 700, 'currency' => 'INR', 'status' => 'Failed', 'razorpay_order_id' => 'order_A004'],
            ['id' => 5, 'user_id' => 4, 'order_number' => 'ORD005', 'total_amount' => 12000, 'currency' => 'INR', 'status' => 'Paid', 'razorpay_order_id' => 'order_A005'],
            ['id' => 6, 'user_id' => 5, 'order_number' => 'ORD006', 'total_amount' => 3500, 'currency' => 'INR', 'status' => 'Pending', 'razorpay_order_id' => 'order_A006'],
            ['id' => 7, 'user_id' => 6, 'order_number' => 'ORD007', 'total_amount' => 6500, 'currency' => 'INR', 'status' => 'Paid', 'razorpay_order_id' => 'order_A007'],
            ['id' => 8, 'user_id' => 7, 'order_number' => 'ORD008', 'total_amount' => 2200, 'currency' => 'INR', 'status' => 'Paid', 'razorpay_order_id' => 'order_A008'],
            ['id' => 9, 'user_id' => 8, 'order_number' => 'ORD009', 'total_amount' => 55800, 'currency' => 'INR', 'status' => 'Failed', 'razorpay_order_id' => 'order_A009'],
            ['id' => 10, 'user_id' => 9, 'order_number' => 'ORD010', 'total_amount' => 800, 'currency' => 'INR', 'status' => 'Paid', 'razorpay_order_id' => 'order_A010'],
        ];

        foreach ($orders as $order) {
            Order::updateOrCreate(['id' => $order['id']], $order);
        }
    }
}
