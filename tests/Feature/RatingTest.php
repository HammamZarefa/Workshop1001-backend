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
    /** @test */
public function product_rating_average()
{
    $user = User::factory()->create();
    $product = Product::factory()->create();

    
    Rating::create([
        'product_id' => $product->id,
        'user_id' => $user->id,
        'rating' => 4,
        'comment' => 'Good'
    ]);

    Rating::create([
        'product_id' => $product->id,
        'user_id' => $user->id,
        'rating' => 2,
        'comment' => 'Not bad'
    ]);

    
    $response = $this->getJson("/api/v1/products/{$product->id}");

    $response->assertStatus(200);

    
    $response->assertJson([
        'average_rating' => 3.0
    ]);
}

}
