<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Coupon;


class CouponSeeder extends Seeder
{
    public function run(): void
    {
        // Create 20 random coupons
        Coupon::factory()->count(20)->create();

        // Create some fixed examples
        Coupon::create([
            'name' => 'Welcome Discount',
            'code' => 'WELCOME10',
            'type' => 'percentage',
            'value' => 10,
            'min_order_amount' => 0,
            'usage_limit' => 100,
            'usage_limit_per_user' => 1,
            'start_date' => now(),
            'expiration_date' => now()->addMonth(),
        ]);

        Coupon::create([
            'name' => 'Flat 15 Off',
            'code' => 'FLAT15',
            'type' => 'fixed',
            'value' => 15,
            'min_order_amount' => 50,
            'usage_limit' => 50,
            'usage_limit_per_user' => 2,
            'start_date' => now(),
            'expiration_date' => now()->addDays(45),
        ]);
    }
}
