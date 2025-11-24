<?php

namespace Tests\Feature\Errors;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

class AuthenticationExceptionTest extends TestCase
{
    use RefreshDatabase;   

    public function test_authenticated()
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $response = $this->getJson('/api/v1/user');

        $response->assertStatus(200);
    }
}
