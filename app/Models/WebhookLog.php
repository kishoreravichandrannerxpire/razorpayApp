<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WebhookLog extends Model
{
    protected $fillable = [
        'event_id',
        'event_type',
        'razorpay_order_id',
        'razorpay_payment_id',
        'signature',
        'payload',
        'processed',
        'received_at',
    ];

    protected $casts = [
        'payload' => 'array',
        'processed' => 'boolean',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'razorpay_order_id', 'razorpay_order_id');
    }

    public function payment()
    {
        return $this->belongsTo(Payment::class, 'razorpay_payment_id', 'razorpay_payment_id');
    }
}
