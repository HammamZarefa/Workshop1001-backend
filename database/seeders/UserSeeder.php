<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

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
        'password' => Hash::make('password123'),
        'phone' => '0901234567',
        'address' => 'Riyadh, Saudi Arabia',
    ],
    [
        'first_name' => 'Sara',
        'last_name' => 'Mohammed',
        'email' => 'sara.mohammed@example.com',
        'password' => Hash::make('password123'),
        'phone' => '0902345678',
        'address' => 'Jeddah, Saudi Arabia',
    ],
    [
        'first_name' => 'Omar',
        'last_name' => 'Hassan',
        'email' => 'omar.hassan@example.com',
        'password' => Hash::make('password123'),
        'phone' => '0903456789',
        'address' => 'Dammam, Saudi Arabia',
    ],
    [
        'first_name' => 'Lina',
        'last_name' => 'Abdullah',
        'email' => 'lina.abdullah@example.com',
        'password' => Hash::make('password123'),
        'phone' => '0904567890',
        'address' => 'Khobar, Saudi Arabia',
    ],
    [
        'first_name' => 'Admin',
        'last_name' => 'Super',
        'email' => 'admin@example.com',
        'password' => Hash::make('password'),
        'is_admin' => true,
        'is_active' => true,
    ],
];


        foreach ($users as $user) {
            User::create($user);
        }
    }

}
