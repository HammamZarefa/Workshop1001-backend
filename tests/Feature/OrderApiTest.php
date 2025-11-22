<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\Cart;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderApiTest extends TestCase
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

//    public function test_cannot_create_order_with_wrong_item_price()
//    {
//        $this->actingAs($this->user, 'sanctum');
//
//        $payload = [
//            'shipping_address' => 'Amman, Jordan',
//            'currency' => 'USD',
//            'items' => [
//                [
//                    'product_id' => $this->product->id,
//                    'price' => $this->product->price + 1, // wrong price
//                    'quantity' => 1,
//                    'note' => null,
//                ]
//            ]
//        ];
//
//        $response = $this->postJson('/api/v1/orders', $payload);
//
//        $response->assertStatus(422);
//    }

    public function test_can_create_order()
    {
        $this->actingAs($this->user, 'sanctum');

        $payload = [
            'shipping_address' => 'Amman, Jordan',
            'currency' => 'USD',
            'items' => [
                [
                    'product_id' => $this->product->id,
                    'price' => $this->product->price,
                    'quantity' => 2,
                    'note' => null,
                ]
            ]
        ];

        $response = $this->postJson('/api/v1/orders', $payload);


        $response->assertStatus(201)
            ->assertJson([
                'status' => 201,
                'message' => 'Order created successfully',
            ]);

        $this->assertDatabaseHas('orders', ['user_id' => $this->user->id]);
        $this->assertDatabaseHas('order_items', ['product_id' => $this->product->id]);
    }

    public function test_creating_order_marks_active_cart_as_completed()
    {
        $this->actingAs($this->user, 'sanctum');

        // Given an active cart for this user
        $cart = Cart::factory()->for($this->user)->create([
            'status' => 'pending',
        ]);

        // And a valid order payload
        $payload = [
            'shipping_address' => 'Amman, Jordan',
            'currency' => 'USD',
            'items' => [
                [
                    'product_id' => $this->product->id,
                    'price' => $this->product->price,
                    'quantity' => 1,
                    'note' => null,
                ],
            ],
        ];

        $response = $this->postJson('/api/v1/orders', $payload);

        $response->assertStatus(201);

        // The previously active cart should now be completed
        $this->assertDatabaseHas('carts', [
            'id' => $cart->id,
            'status' => 'completed',
        ]);
    }

    public function test_can_fetch_order()
    {
        $this->actingAs($this->user, 'sanctum');

        $order = Order::factory()->for($this->user)->create();
        $order->items()->create([
            'product_id' => $this->product->id,
            'price' => 50,
            'quantity' => 1
        ]);

        $response = $this->getJson("/api/v1/orders/{$order->id}");

        $response->assertStatus(200)
            ->assertJson([
                'status' => 200,
                'message' => 'Order fetched',
            ]);

    }

}
