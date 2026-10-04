<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Payment;
use App\Models\User;
use App\Models\WebhookLog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RazorpayWebhookTest extends TestCase
{
    use RefreshDatabase;

    private string $secret = 'test_webhook_secret';

    protected function setUp(): void
    {
        parent::setUp();

        config([
            'services.razorpay.key' => 'rzp_test_key',
            'services.razorpay.secret' => 'rzp_test_secret',
            'services.razorpay.webhook_secret' => $this->secret,
        ]);
    }

    private function makeOrder(string $status = 'Pending'): Order
    {
        return Order::create([
            'user_id' => User::factory()->create()->id,
            'order_number' => 'ORD-TEST' . uniqid(),
            'total_amount' => 500,
            'currency' => 'INR',
            'status' => $status,
            'razorpay_order_id' => 'order_T1',
        ]);
    }

    private function sendWebhook(string $event, string $eventId, ?string $signature = null)
    {
        $payload = json_encode([
            'event' => $event,
            'payload' => ['payment' => ['entity' => [
                'id' => 'pay_T1',
                'order_id' => 'order_T1',
                'amount' => 50000,
                'method' => 'upi',
            ]]],
        ]);

        $signature ??= hash_hmac('sha256', $payload, $this->secret);

        return $this->call('POST', '/razorpay/webhook', [], [], [], [
            'CONTENT_TYPE' => 'application/json',
            'HTTP_X_RAZORPAY_SIGNATURE' => $signature,
            'HTTP_X_RAZORPAY_EVENT_ID' => $eventId,
        ], $payload);
    }

    public function test_same_event_delivered_twice_is_processed_once()
    {
        $order = $this->makeOrder();

        $this->sendWebhook('payment.captured', 'evt_dup')->assertOk();
        $this->sendWebhook('payment.captured', 'evt_dup')->assertOk();

        $this->assertEquals('Paid', $order->fresh()->status);
        $this->assertEquals(1, WebhookLog::where('event_id', 'evt_dup')->count());
        $this->assertEquals(1, Payment::where('razorpay_payment_id', 'pay_T1')->count());
    }

    public function test_invalid_signature_is_rejected()
    {
        $order = $this->makeOrder();

        $this->sendWebhook('payment.captured', 'evt_bad', 'wrong-signature')->assertStatus(400);

        $this->assertEquals('Pending', $order->fresh()->status);
        $this->assertEquals(0, WebhookLog::count());
    }

    public function test_failed_event_does_not_undo_a_paid_order()
    {
        $order = $this->makeOrder('Paid');

        $this->sendWebhook('payment.failed', 'evt_late_fail')->assertOk();

        $this->assertEquals('Paid', $order->fresh()->status);
    }
}