<?php

namespace Tests\Feature;
use Tests\TestCase;
use App\Models\User;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PaymentTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_list_his_payments()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $order = Order::factory()->create(['user_id' => $user->id]);
        $payment = Payment::factory()->create(['order_id' => $order->id]);

        $response = $this->getJson('/api/v1/payment');

        $response->assertStatus(200)
            ->assertJsonFragment([
                'id' => $payment->id,
            ]);
    }

    /** @test */
    public function user_cannot_list_payments_if_not_authenticated()
    {
        $response = $this->getJson('/api/v1/payment');

        $response->assertStatus(401);
    }

    /** @test */
    public function user_can_create_a_payment()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $order = Order::factory()->create(['user_id' => $user->id]);

        $data = [
            'order_id' => $order->id,
            'status' => 'pending',
            'amount' => 120,
            'currency' => 'USD'
        ];

        $response = $this->postJson('/api/v1/payment', $data);

        $response->assertStatus(201)
            ->assertJsonFragment([
                'status' => 'pending',
                'currency' => 'USD'
            ]);
    }

    /** @test */
    public function user_cannot_create_payment_with_invalid_data()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->postJson('/api/v1/payment', [
            'amount' => -10
        ]);

        $response->assertStatus(422);
    }

    /** @test */
    public function user_can_view_specific_payment_that_he_owns()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $order = Order::factory()->create(['user_id' => $user->id]);
        $payment = Payment::factory()->create(['order_id' => $order->id]);

        $response = $this->getJson("/api/v1/payment/{$payment->id}");

        $response->assertStatus(200)
            ->assertJsonFragment([
                'id' => $payment->id
            ]);
    }

    /** @test */
    public function user_cannot_view_payment_he_does_not_own()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        $this->actingAs($user);

        $order = Order::factory()->create(['user_id' => $otherUser->id]);
        $payment = Payment::factory()->create(['order_id' => $order->id]);

        $response = $this->getJson("/api/v1/payment/{$payment->id}");

        $response->assertStatus(404);
    }

}
