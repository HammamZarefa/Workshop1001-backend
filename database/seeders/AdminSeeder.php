<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
       
        User::factory()->state([
            'first_name' => 'Admin',
            'last_name'  => 'Super',
            'email' => 'admin@example.com',
            'phone' => '0912345678',
            'address' => 'Admin Office',
            'is_admin' => true,
            'is_active' => true,
            'password' => bcrypt('admin12345'),
        ])->create();
    }
}
