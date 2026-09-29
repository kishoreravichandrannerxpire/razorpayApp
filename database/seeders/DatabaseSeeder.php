<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            ProductSeeder::class,
            OrderSeeder::class,
            OrderItemSeeder::class,
            PaymentSeeder::class,
            WebhookLogSeeder::class,
        ]);

        // PostgreSQL only: seeders above insert fixed ids, so move each
        // table's id counter forward before inserting rows without an id.
        $this->resetPostgresSequences();

        $this->call([
            AdminUserSeeder::class,
        ]);
    }

    private function resetPostgresSequences(): void
    {
        if (DB::getDriverName() !== 'pgsql') {
            return;
        }

        foreach (['users', 'products', 'orders', 'order_items', 'payments', 'webhook_logs'] as $table) {
            DB::statement(
                "SELECT setval(pg_get_serial_sequence('{$table}', 'id'), COALESCE((SELECT MAX(id) FROM {$table}), 1))"
            );
        }
    }
}