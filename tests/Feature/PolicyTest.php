<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;

class PolicyTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Seed the roles, permissions, and users
        $this->seed(\Database\Seeders\RolePermissionSeeder::class);
    }

    /** @test */
    public function it_creates_all_permissions_and_roles_and_users()
    {
        // Check permissions count
        $this->assertEquals(35, Permission::count(), "All permissions should be created");

        // Check roles count
        $this->assertEquals(7, Role::count(), "All roles should be created");

        // Check each role has at least one permission
        foreach (Role::all() as $role) {
            $this->assertTrue($role->permissions->count() > 0, "Role {$role->name} should have permissions");
        }

        // Check users exist and have correct role
        $roleUserMap = [
            'admin@example.com' => 'admin',
            'product.admin@example.com' => 'product_manager',
            'order.admin@example.com' => 'order_manager',
            'category.admin@example.com' => 'category_manager',
            'banner.admin@example.com' => 'banner_manager',
            'coupon.admin@example.com' => 'coupon_manager',
            'payment.admin@example.com' => 'payment_manager',
        ];

        foreach ($roleUserMap as $email => $roleName) {
            $user = User::where('email', $email)->first();
            $this->assertNotNull($user, "User $email should exist");
            $this->assertEquals($roleName, $user->role->name, "User $email should have role $roleName");
        }
    }

    /** @test */
    public function all_roles_have_correct_permissions()
    {
        $rolesPermissions = [
            'admin' => Permission::pluck('name')->toArray(),
            'product_manager' => ['view_products', 'create_products', 'edit_products', 'delete_products'],
            'order_manager' => ['view_orders', 'update_orders', 'cancel_orders'],
            'category_manager' => ['view_categories', 'create_categories', 'edit_categories', 'delete_categories'],
            'banner_manager' => ['view_banners', 'create_banners', 'edit_banners', 'delete_banners'],
            'coupon_manager' => ['view_coupons', 'create_coupons', 'edit_coupons', 'delete_coupons'],
            'payment_manager' => ['view_payments', 'create_payments', 'edit_payments', 'delete_payments'],
        ];

        foreach ($rolesPermissions as $roleName => $perms) {
            $role = Role::where('name', $roleName)->first();
            $this->assertNotNull($role, "Role $roleName should exist");

            $user = User::where('role_id', $role->id)->first();
            $this->assertNotNull($user, "User for role $roleName should exist");
            $this->actingAs($user);

            // Check allowed permissions
            foreach ($perms as $perm) {
                $this->assertTrue($user->hasPermission($perm), "$roleName should have permission $perm");
            }

            // Check disallowed permissions
            $otherPerms = Permission::whereNotIn('name', $perms)->pluck('name');
            foreach ($otherPerms as $perm) {
                $this->assertFalse($user->hasPermission($perm), "$roleName should NOT have permission $perm");
            }
        }
    }

    /** @test */
    public function has_roles_trait_works_correctly()
    {
        $user = User::where('email', 'product.admin@example.com')->first();
        $this->assertTrue($user->hasRole('product_manager'));
        $this->assertFalse($user->hasRole('admin'));
    }

    /** @test */
    public function has_permissions_trait_works_correctly()
    {
        $user = User::where('email', 'product.admin@example.com')->first();
        $this->assertTrue($user->hasPermission('edit_products'));
        $this->assertFalse($user->hasPermission('edit_orders'));
    }
}
