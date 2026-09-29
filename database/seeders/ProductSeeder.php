<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            ['id' => 1, 'product_name' => 'Laptop', 'description' => 'Dell Inspiron', 'price' => 55000, 'stock' => 20, 'status' => 'Active'],
            ['id' => 2, 'product_name' => 'Mouse', 'description' => 'Wireless Mouse', 'price' => 800, 'stock' => 100, 'status' => 'Active'],
            ['id' => 3, 'product_name' => 'Keyboard', 'description' => 'Mechanical Keyboard', 'price' => 2500, 'stock' => 60, 'status' => 'Active'],
            ['id' => 4, 'product_name' => 'Monitor', 'description' => '24 Inch Monitor', 'price' => 12000, 'stock' => 30, 'status' => 'Active'],
            ['id' => 5, 'product_name' => 'Headset', 'description' => 'Gaming Headset', 'price' => 3500, 'stock' => 40, 'status' => 'Active'],
            ['id' => 6, 'product_name' => 'Printer', 'description' => 'HP Printer', 'price' => 9000, 'stock' => 15, 'status' => 'Active'],
            ['id' => 7, 'product_name' => 'Pendrive', 'description' => '64GB USB', 'price' => 700, 'stock' => 80, 'status' => 'Active'],
            ['id' => 8, 'product_name' => 'SSD', 'description' => '1TB SSD', 'price' => 6500, 'stock' => 35, 'status' => 'Active'],
            ['id' => 9, 'product_name' => 'Webcam', 'description' => 'HD Webcam', 'price' => 2800, 'stock' => 25, 'status' => 'Inactive'],
            ['id' => 10, 'product_name' => 'Router', 'description' => 'WiFi Router', 'price' => 2200, 'stock' => 45, 'status' => 'Active'],
        ];

        foreach ($products as $product) {
            Product::updateOrCreate(['id' => $product['id']], $product);
        }
    }
}
