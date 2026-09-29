<?php

namespace Database\Seeders;

use App\Models\Payment;
use Illuminate\Database\Seeder;

class PaymentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $payments = [
            ['id' => 1, 'order_id' => 1, 'razorpay_payment_id' => 'pay001', 'razorpay_order_id' => 'order_A001', 'razorpay_signature' => 'sig001', 'payment_method' => 'UPI', 'amount' => 55800, 'status' => 'Success', 'paid_at' => '2026-07-20 10:30:00'],
            ['id' => 2, 'order_id' => 3, 'razorpay_payment_id' => 'pay003', 'razorpay_order_id' => 'order_A003', 'razorpay_signature' => 'sig003', 'payment_method' => 'Card', 'amount' => 9000, 'status' => 'Success', 'paid_at' => '2026-07-20 11:00:00'],
            ['id' => 3, 'order_id' => 5, 'razorpay_payment_id' => 'pay005', 'razorpay_order_id' => 'order_A005', 'razorpay_signature' => 'sig005', 'payment_method' => 'Net Banking', 'amount' => 12000, 'status' => 'Success', 'paid_at' => '2026-07-21 09:30:00'],
            ['id' => 4, 'order_id' => 7, 'razorpay_payment_id' => 'pay007', 'razorpay_order_id' => 'order_A007', 'razorpay_signature' => 'sig007', 'payment_method' => 'UPI', 'amount' => 6500, 'status' => 'Success', 'paid_at' => '2026-07-21 12:15:00'],
            ['id' => 5, 'order_id' => 8, 'razorpay_payment_id' => 'pay008', 'razorpay_order_id' => 'order_A008', 'razorpay_signature' => 'sig008', 'payment_method' => 'Wallet', 'amount' => 2200, 'status' => 'Success', 'paid_at' => '2026-07-22 02:00:00'],
            ['id' => 6, 'order_id' => 10, 'razorpay_payment_id' => 'pay010', 'razorpay_order_id' => 'order_A010', 'razorpay_signature' => 'sig010', 'payment_method' => 'Card', 'amount' => 800, 'status' => 'Success', 'paid_at' => '2026-07-22 03:30:00'],
        ];

        foreach ($payments as $payment) {
            Payment::updateOrCreate(['id' => $payment['id']], $payment);
        }
    }
}
