<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminAuthControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function admin_can_login_successfully()
    {

        $password = 'password123';
        $admin = User::factory()->create([
            'email' => 'admin@example.com',
            'password' => $password,
             'is_admin' => true,
            'is_active' => true,
        ]);

        $payload = [
            'email' => $admin->email,
            'password' => $password,

        ];

        $response = $this->postJson('/api/v1/admin/login', $payload);

       $response->assertJsonStructure([
    'message',
    'data' => [
        'admin',
        'token',
        'token_type',
    ]
]);
    }

    /** @test */
    public function non_admin_cannot_login_as_admin()
    {
        $user = User::factory()->create([
            'email' => 'user@example.com',
            'password' => Hash::make('password'),
            'is_admin' => false,
            'is_active' => true,
        ]);

        $response = $this->postJson('/api/v1/admin/login', [
            'email' => 'user@example.com',
            'password' => 'password'
        ]);

        $response->assertStatus(401);
    }

    /** @test */
    public function wrong_credentials_cannot_login()
    {
        User::factory()->create([
            'email' => 'admin@example.com',
            'password' => Hash::make('admin12345'),
            'is_admin' => true,
            'is_active' => true,
        ]);

        $response = $this->postJson('/api/v1/admin/login', [
            'email' => 'admin@example.com',
            'password' => 'wrongpassword'
        ]);

        $response->assertStatus(401);
    }

    /** @test */
    public function admin_can_logout_successfully()
    {
        $admin = User::factory()->create([
            'is_admin' => true,
            'is_active' => true,
        ]);

        $token = $admin->createToken('test')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/v1/admin/logout');

        $response->assertStatus(200)
                 ->assertJson([
                     'status' => 200,
                     'message' => 'Admin logged out successfully'
                 ]);
    }

    /** @test */
    public function non_admin_cannot_access_logout_route()
    {
        $user = User::factory()->create([
            'is_admin' => false,
            'is_active' => true,
        ]);

        $token = $user->createToken('test')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/v1/admin/logout');

        $response->assertStatus(400);
    }
}
