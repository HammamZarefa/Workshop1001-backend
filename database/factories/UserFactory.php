<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected $model = User::class;

    /**
     * تعريف بيانات وهمية لكل حقل.
     */
    public function definition()
    {
        return [
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'email' => $this->faker->unique()->safeEmail(),
            'phone' => $this->faker->unique()->phoneNumber(),
            'address' => $this->faker->address(),
            'password' => bcrypt('password'), // كلمة مرور افتراضية
            'is_admin' => false,
            'is_active' => true,
            'firebase_token' => null,
            'fcm_token' => null,
            'email_verified_at' => now(),
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
}
