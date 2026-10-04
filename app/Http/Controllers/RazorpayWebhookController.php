<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use App\Models\WebhookLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Razorpay\Api\Api;

class RazorpayWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $payload = $request->getContent();
        $signature = $request->header('X-Razorpay-Signature');

        if (! $signature) {
            return response()->json(['status' => 'error', 'message' => 'Missing signature'], 400);
        }

        // 1. Verify the request really came from Razorpay
        try {
            $api = new Api(
                config('services.razorpay.key'),
                config('services.razorpay.secret')
            );

            $api->utility->verifyWebhookSignature(
                $payload,
                $signature,
                config('services.razorpay.webhook_secret')
            );
        } catch (\Throwable $e) {
            return response()->json(['status' => 'error', 'message' => 'Invalid signature'], 400);
        }

        $data = json_decode($payload, true);

        if (! is_array($data) || empty($data['event'])) {
            return response()->json(['status' => 'error', 'message' => 'Invalid payload'], 400);
        }

        // 2. Unique id of THIS event. Razorpay sends the same id when it retries.
        $eventId = $request->header('X-Razorpay-Event-Id') ?: hash('sha256', $payload);
        $payment = $data['payload']['payment']['entity'] ?? null;

        // 3. Process once. Log + order update + payment update succeed or fail together.
        try {
            DB::transaction(function () use ($data, $payment, $eventId, $signature) {
                $log = WebhookLog::firstOrCreate(
                    ['event_id' => $eventId],
                    [
                        'event_type' => $data['event'],
                        'razorpay_order_id' => $payment['order_id'] ?? null,
                        'razorpay_payment_id' => $payment['id'] ?? null,
                        'signature' => $signature,
                        'payload' => $data,
                        'processed' => false,
                        'received_at' => now(),
                    ]
                );

                // Lock the log row so two parallel deliveries cannot both process it
                $log = WebhookLog::whereKey($log->id)->lockForUpdate()->first();

                if ($log->processed) {
                    return; // duplicate delivery, already handled
                }

                $this->processEvent($data['event'], $payment);

                $log->update(['processed' => true]);
            });
        } catch (\Throwable $e) {
            report($e);

            // 500 makes Razorpay retry later. Never send exception text back.
            return response()->json(['status' => 'error', 'message' => 'Processing failed'], 500);
        }

        return response()->json(['status' => 'success']);
    }

    private function processEvent(string $event, ?array $payment): void
    {
        if (! $payment || empty($payment['order_id'])) {
            return;
        }

        $order = Order::where('razorpay_order_id', $payment['order_id'])
            ->lockForUpdate()
            ->first();

        if (! $order) {
            return;
        }

        $amount = ($payment['amount'] ?? 0) / 100;

        if ($event === 'payment.captured') {
            // Money received must match what we asked for
            if (abs($amount - $order->total_amount) > 0.01) {
                report(new \RuntimeException(
                    "Webhook amount mismatch for {$order->order_number}: paid {$amount}, expected {$order->total_amount}"
                ));

                return;
            }

            Payment::updateOrCreate(
                ['razorpay_payment_id' => $payment['id']],
                [
                    'order_id' => $order->id,
                    'razorpay_order_id' => $payment['order_id'],
                    'amount' => $amount,
                    'payment_method' => $payment['method'] ?? null,
                    'status' => 'Success',
                    'paid_at' => now(),
                ]
            );

            if ($order->status === 'Pending') {
                $order->update(['status' => 'Paid']);
            } elseif (! in_array($order->status, ['Paid', 'Shipped', 'Refunded'])) {
                // Customer paid but the order was already Failed/Cancelled and stock released
                report(new \RuntimeException(
                    "Payment captured for {$order->order_number} but order is {$order->status}. Needs manual review or refund."
                ));
            }

            return;
        }

        if ($event === 'payment.failed') {
            // Only release stock if the order is still waiting. Never undo a Paid order.
            if ($order->status === 'Pending') {
                $order->markFailedAndRestoreStock();
            }

            Payment::updateOrCreate(
                ['razorpay_payment_id' => $payment['id']],
                [
                    'order_id' => $order->id,
                    'razorpay_order_id' => $payment['order_id'],
                    'amount' => $amount,
                    'payment_method' => $payment['method'] ?? null,
                    'status' => 'Failed',
                ]
            );
        }
    }
}