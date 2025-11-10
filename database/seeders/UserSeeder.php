<?php

namespace Database\Seeders;

<<<<<<< HEAD
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User; 
=======
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
>>>>>>> 94462bd3665a2eebf59768ce840d2040098fac63

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {


        $users = [
            [
                'first_name' => 'Ahmed',
                'last_name' => 'Ali',
                'email' => 'ahmed.ali@example.com',
                'password' => 'password123',
                'phone' => '0901234567',
                'address' => 'Riyadh, Saudi Arabia',
            ],
            [
                'first_name' => 'Sara',
                'last_name' => 'Mohammed',
                'email' => 'sara.mohammed@example.com',
                'password' => 'password123',
                'phone' => '0902345678',
                'address' => 'Jeddah, Saudi Arabia',
            ],
            [
                'first_name' => 'Omar',
                'last_name' => 'Hassan',
                'email' => 'omar.hassan@example.com',
                'password' => 'password123',
                'phone' => '0903456789',
                'address' => 'Dammam, Saudi Arabia',
            ],
            [
                'first_name' => 'Lina',
                'last_name' => 'Abdullah',
                'email' => 'lina.abdullah@example.com',
                'password' => 'password123',
                'phone' => '0904567890',
                'address' => 'Khobar, Saudi Arabia',
            ],
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }

}
