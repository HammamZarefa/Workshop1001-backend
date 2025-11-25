<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminFactory extends Factory
{
    protected $model = User::class;

    public function definition()
    {
        return [
            'first_name' => 'Admin',
            'last_name'  => 'Super',
            'email' => 'admin@example.com',
            'phone' => '0912345678',
            'address' => 'Admin Office',
            'is_admin' => true,
            'is_active' => true,
            'password' => bcrypt('admin12345'),
            'email_verified_at' => now(),
            'remember_token' => Str::random(10),
        ];
    }
}
