<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Coupon;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AdminCouponTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $user = User::factory()->create([
            'is_admin' => true,
        ]);

        $this->actingAs($user);
    }

    public function test_can_create_coupon()
    {
        $response = $this->post(route('admin.coupons.store'), [
            'name' => 'Test Coupon',
            'code' => 'TEST123',
            'type' => 'fixed',
            'value' => 10,
        ]);

        $response->assertRedirect(route('admin.coupons.index'));
        $this->assertDatabaseHas('coupons', ['code' => 'TEST123']);
    }

    public function test_can_update_coupon()
    {
        $coupon = Coupon::factory()->create(['code' => 'OLD']);

        $response = $this->put(route('admin.coupons.update', $coupon->id), [
            'name' => 'Updated',
            'code' => 'NEWCODE',
        ]);

        $response->assertRedirect(route('admin.coupons.index'));
        $this->assertDatabaseHas('coupons', ['code' => 'NEWCODE']);
    }

    public function test_cannot_update_coupon_to_duplicate_code()
    {
        $couponA = Coupon::factory()->create(['code' => 'DUPLICATE']);
        $couponB = Coupon::factory()->create(['code' => 'ORIG']);

        $response = $this->from(route('admin.coupons.edit', $couponB->id))
            ->put(route('admin.coupons.update', $couponB->id), [
                'name' => 'Attempt Duplicate',
                'code' => 'DUPLICATE',
            ]);

        $response->assertStatus(422);
        $response->assertJson([
            'success' => false,
            'message' => 'Validation error',
            'errors' => [
                'code' => ['The code has already been taken.'],
            ],
        ]);

        $this->assertDatabaseHas('coupons', [
            'id' => $couponB->id,
            'code' => 'ORIG',
        ]);
    }

    public function test_can_delete_coupon()
    {
        $coupon = Coupon::factory()->create();

        $response = $this->delete(route('admin.coupons.destroy', $coupon->id));

        $response->assertRedirect(route('admin.coupons.index'));
        $this->assertDatabaseMissing('coupons', ['id' => $coupon->id]);
    }
}
