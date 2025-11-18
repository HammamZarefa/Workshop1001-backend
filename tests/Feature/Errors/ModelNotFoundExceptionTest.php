<?php

namespace Tests\Feature\Errors;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

class ModelNotFoundExceptionTest extends TestCase
{
    use RefreshDatabase;
public function test_model_not_found()
{
    Sanctum::actingAs(User::factory()->create());

    $response = $this->getJson('/api/v1/users/9999');

    $response->assertStatus(404)
             ->assertJson([
                 'success' => false,
                 'message' => 'Resource not found',
                 'errors' => []
             ]);
}
}
