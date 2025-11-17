<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
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

        // إنشاء بيانات أساسية
        $this->user = User::factory()->create();
        $this->product = Product::factory()->create();
    }

    public function test_can_create_order()
    {
        $this->actingAs($this->user, 'sanctum');

        $payload = [
            'shipping_address' => 'Amman, Jordan',
            'currency' => 'USD',
            'items' => [
                [
                    'product_id' => $this->product->id,
                    'price' => 100,
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
