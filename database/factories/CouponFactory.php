<?php

namespace Database\Factories;

use App\Models\Coupon;
use Illuminate\Database\Eloquent\Factories\Factory;

class CouponFactory extends Factory
{
    protected $model = Coupon::class;

    public function definition(): array
    {
        $type = $this->faker->randomElement(['fixed', 'percentage']);

        return [
            'name' => $this->faker->words(2, true),
            'code' => strtoupper($this->faker->unique()->bothify('COUPON-####')),
            'type' => $type,
            'value' => $type === 'percentage'
                ? $this->faker->numberBetween(5, 50)
                : $this->faker->randomFloat(2, 5, 100),

            'min_order_amount' => $this->faker->randomFloat(2, 0, 200),

            'usage_limit' => $this->faker->numberBetween(10, 100),
            'usage_limit_per_user' => $this->faker->numberBetween(1, 5),

            'start_date' => now()->subDays(rand(1, 10)),
            'expiration_date' => now()->addDays(rand(10, 60)),
        ];
    }
}
