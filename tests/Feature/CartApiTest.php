<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartApiTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected Product $product;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->product = Product::factory()->create();
    }

    public function test_cannot_update_cart_with_wrong_item_price()
    {
        $this->actingAs($this->user, 'sanctum');

        $payload = [
            'items' => [
                [
                    'product_id' => $this->product->id,
                    'price' => $this->product->price + 1, // wrong price
                    'quantity' => 1,
                ]
            ]
        ];

        $response = $this->postJson('/api/v1/carts', $payload);

        $response->assertStatus(422);
    }

    public function test_can_update_cart_with_valid_prices()
    {
        $this->actingAs($this->user, 'sanctum');

        $payload = [
            'items' => [
                [
                    'product_id' => $this->product->id,
                    'price' => $this->product->price, // exact current price
                    'quantity' => 2,
                ]
            ]
        ];

        $response = $this->postJson('/api/v1/carts', $payload);

        $response->assertStatus(201)
            ->assertJson([
                'status' => 201,
                'message' => 'Cart updated successfully',
            ]);

        $this->assertDatabaseHas('carts', ['user_id' => $this->user->id]);
        $this->assertDatabaseHas('cart_items', ['product_id' => $this->product->id]);
    }
}
