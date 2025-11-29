<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    public function definition(): array
    {
        return [
            'category_id' => Category::inRandomOrder()->first()->id ?? Category::factory(),
            'title' => $this->faker->words(3, true),
            'description' => $this->faker->paragraph(),
            'price' => $this->faker->numberBetween(50, 2000),
            'currency' => 'SAR',
            'stock' => $this->faker->numberBetween(1, 100),
            'is_active' => $this->faker->boolean(90),
            'is_featured' => $this->faker->boolean(30),
            'is_special' => $this->faker->boolean,
            'colors' => [$this->faker->hexColor],
        ];
    }
}
