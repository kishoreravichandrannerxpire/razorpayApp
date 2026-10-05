<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartTest extends TestCase
{
    use RefreshDatabase;

    public function test_add_to_cart_does_not_reduce_stock()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create(['stock' => 100]);

        $this->actingAs($user)->post('/cart/add', [
            'product_id' => $product->id,
            'quantity' => 10,
        ]);

        $this->assertEquals(999, $product->fresh()->stock);
    }

   public function test_checkout_fails_when_stock_is_zero()
{
    $user = User::factory()->create();
    $product = Product::factory()->create(['stock' => 0]);

    // Cart ah session la manual ah set pannunga (real flow madhiri)
    session()->put('cart', [
        $product->id => [
            'id' => $product->id,
            'name' => $product->product_name,
            'price' => (float) $product->price,
            'quantity' => 1,
            'description' => $product->description,
        ],
    ]);

    $response = $this->actingAs($user)->post('/payment/create');

    $response->assertSessionHas('error');
    $this->assertDatabaseMissing('orders', ['user_id' => $user->id]);
}
}