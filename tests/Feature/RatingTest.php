<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use App\Models\Rating;
class RatingTest extends TestCase
{
    /**
     * A basic feature test example.
     */
       use RefreshDatabase;
       /** @test */
     public function user_add_rating_to_product()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();

        $response = $this->actingAs($user)->postJson('/api/v1/ratings', [
            'product_id' => $product->id,
            'rating' => 5,
            'comment' => 'Great product!'
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('ratings', [
            'product_id' => $product->id,
            'rating' => 5,
        ]);
    }
}
