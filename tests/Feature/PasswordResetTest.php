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
    public function forgot_password_sends_notification_with_custom_frontend_url(): void
    {
        Notification::fake();
        config(['app.frontend_url' => 'https://frontend.example']);

        $user = User::factory()->create([
            'email' => 'john.doe@example.com',
        ]);

        $res = $this->postJson('/api/v1/forgot-password', [
            'email' => $user->email,
        ]);

        $res->assertStatus(200)
            ->assertJson([
                'message' => 'If an account exists, a reset link has been sent to this email.'
            ]);

        Notification::assertSentTo($user, ResetPassword::class, function ($notification) use ($user) {
            $mail = $notification->toMail($user);
            $actionUrl = $mail->actionUrl ?? null;
            $this->assertIsString($actionUrl);
            $this->assertStringStartsWith('https://frontend.example/reset-password?token=', $actionUrl);
            $this->assertStringContainsString('email=' . urlencode($user->email), $actionUrl);
            return true;
        });
    }

    /** @test */
    public function reset_password_notification_always_uses_custom_frontend_url()
    {
        Notification::fake();
        config(['app.frontend_url' => 'https://frontend.example']);

        $user = User::factory()->create([
            'email' => 'customurl@example.com',
        ]);

        // إرسال رابط reset
        $this->postJson('/api/v1/forgot-password', [
            'email' => $user->email,
        ])->assertStatus(200);

        // التأكد من أن كل Notification يولد الرابط المخصص
        Notification::assertSentTo($user, \Illuminate\Auth\Notifications\ResetPassword::class, function ($notification) use ($user) {
            $mail = $notification->toMail($user);
            $actionUrl = $mail->actionUrl ?? null;

            $this->assertNotNull($actionUrl);
            $this->assertStringStartsWith('https://frontend.example/reset-password?token=', $actionUrl);
            $this->assertStringContainsString('email=' . urlencode($user->email), $actionUrl);

            return true;
        });
    }

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

    /** @test */
    public function cannot_reset_password_when_confirmation_does_not_match()
    {
        $user = User::factory()->create([
            'email' => 'mismatch@example.com',
            'password' => 'OriginalPass123!'
        ]);

        $token = Password::broker()->createToken($user);

        $payload = [
            'token' => $token,
            'email' => $user->email,
            'password' => 'NewPassword123!',
            'password_confirmation' => 'DifferentPassword123!',
        ];

        $res = $this->postJson('/api/v1/reset-password', $payload);

        $res->assertStatus(422)
            ->assertJsonValidationErrors(['password']);

        // Ensure password did not change
        $this->assertTrue(Hash::check('OriginalPass123!', $user->fresh()->password));
    }

    /** @test */
    public function after_reset_old_password_fails_and_new_password_logs_in()
    {
        $user = User::factory()->create([
            'email' => 'login-check@example.com',
            'password' => 'OldPassword123!'
        ]);

        $token = Password::broker()->createToken($user);

        // Perform reset
        $this->postJson('/api/v1/reset-password', [
            'email' => $user->email,
            'token' => $token,
            'password' => 'NewPassword123!',
            'password_confirmation' => 'NewPassword123!',
        ])->assertStatus(200);

        // Old password should now fail
        $this->postJson('/api/v1/login', [
            'email' => $user->email,
            'password' => 'OldPassword123!'
        ])->assertStatus(401);

        // New password should succeed and return token
        $this->postJson('/api/v1/login', [
            'email' => $user->email,
            'password' => 'NewPassword123!'
        ])->assertStatus(200)
            ->assertJsonStructure(['user', 'token', 'token_type']);
    }
}
