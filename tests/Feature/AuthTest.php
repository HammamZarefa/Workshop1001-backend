<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{

    use RefreshDatabase;

    /** @test */
    public function user_can_register()
    {
        $payload = [
            'first_name' => 'User',
            'last_name' => 'Userr',
            'email' => 'useruser@example.com',
            'password' => 'password123',
            'phone' => '0999999999',
            'address' => 'sssssssss',
        ];

        $response = $this->postJson('/api/v1/register', $payload);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'user',
                'token',
                'token_type',
            ]);

        $this->assertDatabaseHas('users', [
            'email' => $payload['email'],
            'first_name' => $payload['first_name'],
            'last_name' => $payload['last_name'],
        ]);
    }


    /** @test */
    public function user_can_login()
    {
        $password = 'password123';
        $user = User::factory()->create([
            'email' => 'loginuser@example.com',
            'password' => $password,
        ]);

        $payload = [
            'email' => $user->email,
            'password' => $password,
        ];

        $response = $this->postJson('/api/v1/login', $payload);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'user',
                'token',
                'token_type',
            ]);
    }

 /** @test */

    public function user_cant_login()
    {
        $password = '123';
        $user = User::factory()->create([
            'email' => 'loginuser@example.com',
            'password' => $password,
        ]);

        $payload = [
            'email' => $user->email,
            'password' => $password,
        ];

    $response = $this->postJson('/api/v1/login', $payload);

    $response->assertStatus(422)
        ->assertJson([
            "message" => "The password field must be at least 6 characters.",
            "errors" => [
                "password" => [
                    "The password field must be at least 6 characters."
                ]
            ]
        ]);
    }
     /** @test */

public function user_can_logout()
{
    $user = User::factory()->create();

    $token = $user->createToken('auth_token')->plainTextToken;

    $logoutResponse = $this->withHeaders([
        'Authorization' => 'Bearer ' . $token,
    ])->postJson('/api/v1/logout');

    $logoutResponse->assertStatus(200)
        ->assertJson([
            'message' => 'Logged out successfully.'
        ]);


}






}
