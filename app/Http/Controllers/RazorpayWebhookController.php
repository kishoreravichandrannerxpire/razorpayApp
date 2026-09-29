<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use App\Models\WebhookLog;
use Illuminate\Http\Request;
use Razorpay\Api\Api;
use Exception;

class RazorpayWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $payload = $request->getContent();
        $signature = $request->header('X-Razorpay-Signature');

        $api = new Api(
            config('services.razorpay.key'),
            config('services.razorpay.secret')
        );

        try {

            // Verify webhook signature
            $api->utility->verifyWebhookSignature(
                $payload,
                $signature,
                config('services.razorpay.webhook_secret')
            );

            $data = json_decode($payload, true);

            // Save webhook log
            $webhookLog = WebhookLog::create([
                'event_id' => $data['payload']['payment']['entity']['id'] ?? ($data['id'] ?? null),
                'event_type' => $data['event'],
                'razorpay_order_id' => $data['payload']['payment']['entity']['order_id'] ?? null,
                'razorpay_payment_id' => $data['payload']['payment']['entity']['id'] ?? null,
                'signature' => $signature,
                'payload' => $data,
                'processed' => false,
                'received_at' => now(),
            ]);

            switch ($data['event']) {

                case 'payment.captured':

                    $payment = $data['payload']['payment']['entity'];

                    $order = Order::where(
                        'razorpay_order_id',
                        $payment['order_id']
                    )->first();

                    if ($order) {

                        $order->update([
                            'status' => 'Paid'
                        ]);

                        Payment::updateOrCreate(
                            [
                                'razorpay_payment_id' => $payment['id']
                            ],
                            [
                                'order_id' => $order->id,
                                'razorpay_order_id' => $payment['order_id'],
                                'amount' => $payment['amount'] / 100,
                                'payment_method' => $payment['method'],
                                'status' => 'Success',
                                'paid_at' => now(),
                            ]
                        );
                    }

                    break;

               case 'payment.failed':

    $payment = $data['payload']['payment']['entity'];

    $order = Order::where(
        'razorpay_order_id',
        $payment['order_id']
    )->first();

    if ($order) {

        $order->markFailedAndRestoreStock();

        Payment::updateOrCreate(
            [
                'razorpay_payment_id' => $payment['id']
            ],
            [
                'order_id' => $order->id,
                'razorpay_order_id' => $payment['order_id'],
                'amount' => $payment['amount'] / 100,
                'payment_method' => $payment['method'],
                'status' => 'Failed',
            ]
        );
    }

    break;
            }

            $webhookLog->update(['processed' => true]);

            return response()->json([
                'status' => 'success'
            ]);
        } catch (Exception $e) {

            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 400);
        }
    }
}
