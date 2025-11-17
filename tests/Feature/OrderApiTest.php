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

    /** @test */
    public function test_can_create_order(): void
    {
        $payload = [
            'user_id' => $this->user->id,
            'shipping_address' => 'Amman, Jordan',
            'currency' => 'USD',
            'items' => [
                [
                    'product_id' => $this->product->id,
                    'price' => 100,
                    'quantity' => 2
                ]
            ]
        ];

        $response = $this->postJson('/api/v1/orders', $payload);

        $response->assertStatus(201)
            ->assertJson([
                'status' => 201,
                'message' => 'Order created successfully',
            ]);

        $this->assertDatabaseHas('orders', [
            'user_id' => $this->user->id,
            'currency' => 'USD',
        ]);

        $this->assertDatabaseHas('order_items', [
            'product_id' => $this->product->id,
            'price' => 100,
            'quantity' => 2,
        ]);
    }

    /** @test */
    public function test_can_fetch_order(): void
    {
        $order = Order::factory()->for($this->user)->create();

        $response = $this->getJson("/api/v1/orders/{$order->id}");

        $response->assertStatus(200)
            ->assertJson([
                'status' => 200,
                'message' => 'Order fetched',
            ]);
    }

    /** @test */
    public function test_can_update_order(): void
    {
        $order = Order::factory()->for($this->user)->create([
            'shipping_address' => 'Old Address'
        ]);

        $payload = [
            'shipping_address' => 'New Address',
            'status' => 'accepted',
        ];

        $response = $this->putJson("/api/v1/orders/{$order->id}", $payload);

        $response->assertStatus(200)
            ->assertJson([
                'status' => 200,
                'message' => 'Order updated successfully',
            ]);

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'shipping_address' => 'New Address',
            'status' => 'accepted'
        ]);
    }

    /** @test */
    public function test_can_delete_order(): void
    {
        $order = Order::factory()->for($this->user)->create();

        $response = $this->deleteJson("/api/v1/orders/{$order->id}");

        $response->assertStatus(200)
            ->assertJson([
                'status' => 200,
                'message' => 'Order deleted successfully',
            ]);

        $this->assertDatabaseMissing('orders', [
            'id' => $order->id
        ]);
    }
}
