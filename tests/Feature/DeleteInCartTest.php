<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use App\Models\User;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

class DeleteInCartTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_remove_item_from_cart()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $product = Product::factory()->create();
        $cart = Cart::factory()->create(['user_id' => $user->id]);
        $cartItem = CartItem::factory()->create([
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'quantity' => 2,
        ]);

        $response = $this->deleteJson(route('carts.items.remove', $cartItem->id));

      $response->assertStatus(200)
         ->assertJson([
             'message' => 'Cart fetched',
         ]);

        $this->assertDatabaseMissing('cart_items', ['id' => $cartItem->id]);
    }

    public function test_user_cannot_remove_others_cart_item()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        Sanctum::actingAs($user);

        $product = Product::factory()->create();
        $cart = Cart::factory()->create(['user_id' => $otherUser->id]);
        $cartItem = CartItem::factory()->create([
            'cart_id' => $cart->id,
            'product_id' => $product->id,
        ]);

        $response = $this->deleteJson(route('carts.items.remove', $cartItem->id));

      $response->assertStatus(403)
         ->assertJson([
             'success' => false,
             'message' => 'Unauthorized or invalid item',
         ]);

        $this->assertDatabaseHas('cart_items', ['id' => $cartItem->id]);
    }

    public function test_removing_nonexistent_item_returns_error()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->deleteJson(route('carts.items.remove', 999));

        $response->assertStatus(404)
                 ->assertJson([
                     'success' => false,
                     'message' => 'Resource not found',
                 ]);
    }
}
