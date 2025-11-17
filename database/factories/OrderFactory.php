<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'shipping_address' => $this->faker->address(),
            'coupon_value' => 0,
            'tax_amount' => 0,
            'discount_percentage' => 0,
            'currency' => 'USD',
            'total' => 0,
            'status' => 'pending',
        ];
    }
}
