<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentFactory extends Factory
{
    protected $model = Payment::class;

    public function definition(): array
    {
        return [
            'order_id'  => Order::factory(),
            'provider'  => $this->faker->randomElement(['stripe', 'paypal', 'cash', 'visa']),
            'method'    => $this->faker->randomElement(['card', 'bank', 'cash']),
            'status'    => $this->faker->randomElement(['pending', 'paid', 'failed', 'canceled']),
            'reference' => $this->faker->uuid(),
            'amount'    => $this->faker->randomFloat(2, 10, 500),
            'currency'  => 'USD',
            'paid_at'   => $this->faker->optional()->dateTime(),
            'meta'      => [
                'ip' => $this->faker->ipv4(),
                'device' => $this->faker->userAgent(),
            ],
        ];
    }
}
