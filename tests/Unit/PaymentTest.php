<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PaymentTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function payment_belongs_to_order()
    {
        $order = Order::factory()->create();
        $payment = Payment::factory()->create(['order_id' => $order->id]);

        $this->assertInstanceOf(Order::class, $payment->order);
    }

    /** @test */
    public function meta_is_cast_to_array()
    {
        $payment = Payment::factory()->create([
            'meta' => ['foo' => 'bar']
        ]);

        $this->assertIsArray($payment->meta);
    }

    /** @test */
    public function created_payment_has_correct_attributes()
    {
        $payment = Payment::factory()->create([
            'status' => 'pending',
            'amount' => 50,
        ]);

        $this->assertEquals('pending', $payment->status);
        $this->assertEquals(50, $payment->amount);
    }
}
