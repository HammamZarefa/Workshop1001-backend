<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {


//        User::factory()->create([
//            'first_name' => 'admin',
//            'last_name' => 'admin',
//            'is_admin' => true,
//            'email' => 'admin@admin.com',
//        ]);
        $this->call([
            UserSeeder::class,
        ]);
        $this->call([
    BannerSeeder::class,
     CategorySeeder::class,
    ProductSeeder::class,
    PaymentSeeder::class,
]);
        $this->call(OnboardingSeeder::class);


    }
}
