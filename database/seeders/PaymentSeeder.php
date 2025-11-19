<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\Payment;

class PaymentSeeder extends Seeder
{
    public function run(): void
    {
        $orders = Order::factory()->count(10)->create();
        $orders->each(function ($order) {
            Payment::create([
                'order_id'  => $order->id,
                'provider'  => fake()->randomElement([ 'paypal', 'cash', 'visa']),
                'method'    => fake()->randomElement(['card', 'bank', 'cash']),
                'status'    => fake()->randomElement(['pending', 'paid', 'failed', 'canceled']),
                'reference' => fake()->uuid(),
                'amount'    => fake()->randomFloat(2, 10, 500),
                'currency'  => 'USD',
                'paid_at'   => fake()->optional()->dateTime(),
                'meta'      => [
                    'ip'     => fake()->ipv4(),
                    'device' => fake()->userAgent(),
                ],
            ]);
        });
    }
}
