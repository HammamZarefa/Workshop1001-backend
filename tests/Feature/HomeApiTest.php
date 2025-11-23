<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomeApiTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }

    public function test_can_get_categories()
    {
        $this->actingAs($this->user, 'sanctum');

        Category::factory()->count(3)->create();

        $response = $this->getJson('/api/v1/categories');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'title',
                    ],
                ],
            ]);
    }

    public function test_can_get_products_list()
    {
        $this->actingAs($this->user, 'sanctum');

        Product::factory()->count(3)->create();

        $response = $this->getJson('/api/v1/products');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'category_id',
                        'title',
                        'description',
                        'price',
                        'currency',
                        'stock',
                        'is_active',
                        'is_featured',
                    ],
                ],
            ]);
    }

    public function test_can_get_single_product_by_id()
    {
        $this->actingAs($this->user, 'sanctum');

        $product = Product::factory()->create();

        $response = $this->getJson('/api/v1/products/' . $product->id);

        $response->assertStatus(200)
            ->assertJsonFragment([
                'id' => $product->id,
            ]);
    }

    public function test_can_filter_products_by_category_and_price_and_featured()
    {
        $this->actingAs($this->user, 'sanctum');

        $category = Category::factory()->create();

        // matching product
        Product::factory()->create([
            'category_id' => $category->id,
            'is_featured' => true,
            'price' => 100, // Mutator سيضرب ×100 تلقائياً عند الحفظ
            'is_active' => true,
        ]);

        // non-matching product
        Product::factory()->create([
            'category_id' => $category->id,
            'is_featured' => false,
            'price' => 300,
            'is_active' => true,
        ]);

        $params = [
            'category_id' => $category->id,
            'is_featured' => true,
            'min_price' => 50,
            'max_price' => 150,
        ];

        $response = $this->getJson('/api/v1/products-filter?' . http_build_query($params));

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data');
    }
}
