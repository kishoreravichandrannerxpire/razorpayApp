<?php

namespace Database\Seeders;

use App\Models\OrderItem;
use Illuminate\Database\Seeder;

class OrderItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $items = [
            ['id' => 1, 'order_id' => 1, 'product_id' => 1, 'quantity' => 1, 'price' => 55000, 'subtotal' => 55000],
            ['id' => 2, 'order_id' => 1, 'product_id' => 2, 'quantity' => 1, 'price' => 800, 'subtotal' => 800],
            ['id' => 3, 'order_id' => 2, 'product_id' => 3, 'quantity' => 1, 'price' => 2500, 'subtotal' => 2500],
            ['id' => 4, 'order_id' => 3, 'product_id' => 6, 'quantity' => 1, 'price' => 9000, 'subtotal' => 9000],
            ['id' => 5, 'order_id' => 4, 'product_id' => 7, 'quantity' => 1, 'price' => 700, 'subtotal' => 700],
            ['id' => 6, 'order_id' => 5, 'product_id' => 4, 'quantity' => 1, 'price' => 12000, 'subtotal' => 12000],
            ['id' => 7, 'order_id' => 6, 'product_id' => 5, 'quantity' => 1, 'price' => 3500, 'subtotal' => 3500],
            ['id' => 8, 'order_id' => 7, 'product_id' => 8, 'quantity' => 1, 'price' => 6500, 'subtotal' => 6500],
            ['id' => 9, 'order_id' => 8, 'product_id' => 10, 'quantity' => 1, 'price' => 2200, 'subtotal' => 2200],
            ['id' => 10, 'order_id' => 9, 'product_id' => 1, 'quantity' => 1, 'price' => 55000, 'subtotal' => 55000],
            ['id' => 11, 'order_id' => 9, 'product_id' => 2, 'quantity' => 1, 'price' => 800, 'subtotal' => 800],
            ['id' => 12, 'order_id' => 10, 'product_id' => 2, 'quantity' => 1, 'price' => 800, 'subtotal' => 800],
        ];

        foreach ($items as $item) {
            OrderItem::updateOrCreate(['id' => $item['id']], $item);
        }
    }
}
