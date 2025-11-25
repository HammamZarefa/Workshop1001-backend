<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class UserFactory extends Factory
{
    protected $model = User::class;

    /**
     * تعريف بيانات وهمية لكل حقل.
     */
    public function definition()
    {
        return [
            'first_name' => fake()->firstName(),
            'last_name'  => fake()->lastName(),
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->unique()->phoneNumber(),
            'address' => fake()->address(),
            'email_verified_at' => now(),
            'password' => bcrypt('password'),
            'is_active' => true,
            'is_admin' => false,
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * إضافة صورة افتراضية لكل مستخدم عند إنشائه (Spatie Media)
     */
    public function configure()
    {
        return $this->afterCreating(function (User $user) {
            $user->addMedia(public_path('images/default_avatar.png'))
                ->preservingOriginal()
                ->toMediaCollection('avatars');
        });
    }
        public function admin()
    {
        return $this->state(fn () => [
            'is_admin' => true,
        ]);
    }

}
