<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CouponTest extends TestCase
{
    use RefreshDatabase;

    public function test_percentage_discount_is_calculated_correctly()
    {
        $coupon = Coupon::factory()->create([
            'type' => 'percentage',
            'value' => 10,
        ]);

        $discount = $coupon->calculateDiscount(200);

        $this->assertEquals(20, $discount);
    }

    public function test_fixed_discount_is_calculated_correctly()
    {
        $coupon = Coupon::factory()->create([
            'type' => 'fixed',
            'value' => 50,
        ]);

        $discount = $coupon->calculateDiscount(200);

        $this->assertEquals(50, $discount);
    }

    public function test_coupon_is_invalid_if_min_order_not_reached()
    {
        $user = User::factory()->create();

        $coupon = Coupon::factory()->create([
            'min_order_amount' => 100,
        ]);

        $this->assertFalse($coupon->isValid(50, $user));
    }

    public function test_coupon_is_invalid_if_expired()
    {
        $user = User::factory()->create();

        $coupon = Coupon::factory()->create([
            'expiration_date' => now()->subDay(),
        ]);

        $this->assertFalse($coupon->isValid(200, $user));
    }

    public function test_coupon_is_valid_when_conditions_are_met()
    {
        $user = User::factory()->create();

        $coupon = Coupon::factory()->create([
            'min_order_amount' => 50,
            'expiration_date' => now()->addDays(5),
        ]);

        $this->assertTrue($coupon->isValid(200, $user));
    }
}
