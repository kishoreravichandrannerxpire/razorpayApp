<?php

namespace Database\Seeders;

use App\Models\WebhookLog;
use Illuminate\Database\Seeder;

class WebhookLogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $logs = [
            ['id' => 1, 'event_id' => 'evt001', 'event_type' => 'payment.captured', 'razorpay_order_id' => 'order_A001', 'razorpay_payment_id' => 'pay001', 'signature' => 'sig001', 'payload' => json_encode([]), 'processed' => true, 'received_at' => '2026-07-20 10:31:00'],
            ['id' => 2, 'event_id' => 'evt002', 'event_type' => 'payment.captured', 'razorpay_order_id' => 'order_A003', 'razorpay_payment_id' => 'pay003', 'signature' => 'sig003', 'payload' => json_encode([]), 'processed' => true, 'received_at' => '2026-07-20 11:01:00'],
            ['id' => 3, 'event_id' => 'evt003', 'event_type' => 'payment.failed', 'razorpay_order_id' => 'order_A004', 'razorpay_payment_id' => null, 'signature' => 'sig004', 'payload' => json_encode([]), 'processed' => true, 'received_at' => '2026-07-20 01:00:00'],
            ['id' => 4, 'event_id' => 'evt004', 'event_type' => 'payment.captured', 'razorpay_order_id' => 'order_A005', 'razorpay_payment_id' => 'pay005', 'signature' => 'sig005', 'payload' => json_encode([]), 'processed' => true, 'received_at' => '2026-07-21 09:31:00'],
            ['id' => 5, 'event_id' => 'evt005', 'event_type' => 'payment.captured', 'razorpay_order_id' => 'order_A007', 'razorpay_payment_id' => 'pay007', 'signature' => 'sig007', 'payload' => json_encode([]), 'processed' => true, 'received_at' => '2026-07-21 12:16:00'],
            ['id' => 6, 'event_id' => 'evt006', 'event_type' => 'payment.captured', 'razorpay_order_id' => 'order_A008', 'razorpay_payment_id' => 'pay008', 'signature' => 'sig008', 'payload' => json_encode([]), 'processed' => true, 'received_at' => '2026-07-22 02:01:00'],
            ['id' => 7, 'event_id' => 'evt007', 'event_type' => 'payment.failed', 'razorpay_order_id' => 'order_A009', 'razorpay_payment_id' => null, 'signature' => 'sig009', 'payload' => json_encode([]), 'processed' => true, 'received_at' => '2026-07-22 02:30:00'],
            ['id' => 8, 'event_id' => 'evt008', 'event_type' => 'payment.captured', 'razorpay_order_id' => 'order_A010', 'razorpay_payment_id' => 'pay010', 'signature' => 'sig010', 'payload' => json_encode([]), 'processed' => true, 'received_at' => '2026-07-22 03:31:00'],
        ];

        foreach ($logs as $log) {
            WebhookLog::updateOrCreate(['id' => $log['id']], $log);
        }
    }
}
