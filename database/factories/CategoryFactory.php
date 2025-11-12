<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => $this->faker->word(),
            'is_active' => $this->faker->boolean(80),
        ];
    }
}
