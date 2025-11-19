<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Banner;



class BannerFactory extends Factory
{
    protected $model = Banner::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(3),
            'description' => $this->faker->optional()->paragraph(),
            'link' => $this->faker->optional()->url(),
            'is_active' => $this->faker->boolean(80),
            'sort_order' => $this->faker->numberBetween(0, 100),
        ];
    }
}
