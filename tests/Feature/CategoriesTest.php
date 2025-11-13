<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CategoriesTest extends TestCase
{
    use RefreshDatabase;

    public function test_categories_endpoint_requires_authentication(): void
    {
        $response = $this->getJson('/api/v1/categories');
        $response->assertStatus(401);
    }

    public function test_categories_endpoint_returns_only_active_categories(): void
    {
        Sanctum::actingAs(User::factory()->create());

        Category::factory()->count(3)->create(['is_active' => true]);
        Category::factory()->count(2)->create(['is_active' => false]);

        $response = $this->getJson('/api/v1/categories');

        $response->assertOk()
            ->assertJsonStructure([
                'data' => [
                    ['id', 'title', 'is_active']
                ]
            ]);

        $data = $response->json('data');
        $this->assertCount(3, $data);
        foreach ($data as $item) {
            $this->assertTrue((bool)($item['is_active']));
        }
    }

    public function test_categories_endpoint_returns_empty_array_when_no_active_categories(): void
    {
        Sanctum::actingAs(User::factory()->create());

        Category::factory()->count(3)->create(['is_active' => false]);

        $response = $this->getJson('/api/v1/categories');

        $response->assertOk()
            ->assertJson(['data' => []]);
    }
}
