<?php

namespace App\Console\Commands;

use App\Models\Order;
use Illuminate\Console\Command;

class ExpirePendingOrders extends Command
{
    protected $signature = 'orders:expire-pending {--minutes=30}';

    protected $description = 'Cancel unpaid orders and release their stock';

    public function handle(): int
    {
        $cutoff = now()->subMinutes((int) $this->option('minutes'));
        $expired = 0;

        Order::where('status', 'Pending')
            ->where('created_at', '<', $cutoff)
            ->chunkById(100, function ($orders) use (&$expired) {
                foreach ($orders as $order) {
                    $order->markFailedAndRestoreStock('Cancelled');

                    // Webhook munnadi Paid aakkiyirundhaa Cancelled aagirukkaadhu
                    if ($order->status === 'Cancelled') {
                        $expired++;
                    }
                }
            });

        $this->info("Expired {$expired} pending order(s).");

        return self::SUCCESS;
    }
}