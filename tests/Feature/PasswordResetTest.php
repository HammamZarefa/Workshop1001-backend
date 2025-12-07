<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;
use Tests\TestCase;

class PasswordResetTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function forgot_password_returns_200_and_sends_notification_when_user_exists()
    {
        Notification::fake();

        $user = User::factory()->create([
            'email' => 'john@example.com',
        ]);

        $res = $this->postJson('/api/v1/forgot-password', [
            'email' => $user->email,
        ]);

        $res->assertStatus(200)
            ->assertJson([
                'message' => 'If an account exists, a reset link has been sent to this email.'
            ]);

        Notification::assertSentTo($user, ResetPassword::class);
    }

    /** @test */
    public function forgot_password_returns_200_with_non_existing_email_and_no_notification()
    {
        Notification::fake();

        $res = $this->postJson('/api/v1/forgot-password', [
            'email' => 'missing@example.com',
        ]);

        $res->assertStatus(200)
            ->assertJson([
                'message' => 'If an account exists, a reset link has been sent to this email.'
            ]);

        Notification::assertNothingSent();
    }

    /** @test */
    public function can_reset_password_with_valid_token_and_old_tokens_are_revoked()
    {
        $user = User::factory()->create([
            'email' => 'resetme@example.com',
            'password' => 'old-password',
        ]);

        // create an existing token to ensure it gets revoked on reset
        $user->createToken('auth_token');

        $token = Password::broker()->createToken($user);

        $payload = [
            'token' => $token,
            'email' => $user->email,
            'password' => 'new-secure-password',
            'password_confirmation' => 'new-secure-password',
        ];

        $res = $this->postJson('/api/v1/reset-password', $payload);

        $res->assertStatus(200)
            ->assertJson([
                'message' => 'Your password has been reset successfully.'
            ]);

        // Password actually changed
        $this->assertTrue(Hash::check('new-secure-password', $user->fresh()->password));

        // Tokens revoked
        $this->assertSame(0, $user->tokens()->count());
    }

    /** @test */
    public function cannot_reset_password_with_invalid_token()
    {
        $user = User::factory()->create([
            'email' => 'invalidtoken@example.com',
        ]);

        $payload = [
            'token' => 'invalid-token',
            'email' => $user->email,
            'password' => 'some-password',
            'password_confirmation' => 'some-password',
        ];

        $res = $this->postJson('/api/v1/reset-password', $payload);

        $res->assertStatus(422)
            ->assertJson([
                'message' => 'This password reset token is invalid or has expired.'
            ]);
    }
}
