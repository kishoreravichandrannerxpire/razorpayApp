<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    public function definition(): array
    {
        return [
            'product_name' => fake()->word(),
            'description'  => fake()->sentence(),
            'price'        => fake()->randomFloat(2, 100, 5000),
            'stock'        => 100,
            'status'       => 'Active',
        ];
    }
}