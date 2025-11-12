<?php

namespace Database\Factories;

use App\Models\Onboarding;
use Illuminate\Database\Eloquent\Factories\Factory;

class OnboardingFactory extends Factory
{
    protected $model = Onboarding::class;

    public function definition()
    {
        return [
            'title' => $this->faker->sentence(3),
            'subtitle' => $this->faker->sentence(4),
            'description' => $this->faker->paragraph(),
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Onboarding $onboarding) {
            
            $path = public_path('images/default_onboarding.png');

            if (file_exists($path)) {
                $onboarding->addMedia($path)
                    ->preservingOriginal()
                    ->toMediaCollection('images');
            }
        });
    }
}
