<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        // ---------------------------
        // Create All Permissions
        // ---------------------------
        $permissions = [
            // Products
            'view_products', 'create_products', 'edit_products', 'delete_products',

            // Orders
            'view_orders', 'update_orders', 'cancel_orders',

            // Categories
            'view_categories', 'create_categories', 'edit_categories', 'delete_categories',

            // Banners
            'view_banners', 'create_banners', 'edit_banners', 'delete_banners',

            // Roles
            'view_roles', 'create_roles', 'edit_roles', 'delete_roles',

            // Permissions
            'view_permissions', 'create_permissions', 'edit_permissions', 'delete_permissions',

            // Users
            'view_users', 'create_users', 'edit_users', 'delete_users',

            // Coupons
            'view_coupons', 'create_coupons', 'edit_coupons', 'delete_coupons',

            // Payments
            'view_payments', 'create_payments', 'edit_payments', 'delete_payments',
        ];

        foreach ($permissions as $p) {
            Permission::firstOrCreate(
                ['name' => $p],
                ['label' => ucfirst(str_replace('_', ' ', $p))]
            );
        }

        $allPermissions = Permission::pluck('id')->toArray();

        // ---------------------------
        // Create Roles
        // ---------------------------
        $roles = [
            'admin' => $allPermissions,

            'product_manager' => Permission::whereIn('name', [
                'view_products', 'create_products', 'edit_products', 'delete_products'
            ])->pluck('id')->toArray(),

            'order_manager' => Permission::whereIn('name', [
                'view_orders', 'update_orders', 'cancel_orders'
            ])->pluck('id')->toArray(),

            'category_manager' => Permission::whereIn('name', [
                'view_categories', 'create_categories', 'edit_categories', 'delete_categories'
            ])->pluck('id')->toArray(),

            'banner_manager' => Permission::whereIn('name', [
                'view_banners', 'create_banners', 'edit_banners', 'delete_banners'
            ])->pluck('id')->toArray(),

            'coupon_manager' => Permission::whereIn('name', [
                'view_coupons', 'create_coupons', 'edit_coupons', 'delete_coupons'
            ])->pluck('id')->toArray(),

            'payment_manager' => Permission::whereIn('name', [
                'view_payments', 'create_payments', 'edit_payments', 'delete_payments'
            ])->pluck('id')->toArray(),
        ];

        foreach ($roles as $roleName => $perms) {
            $role = Role::firstOrCreate(
                ['name' => $roleName],
                ['label' => ucfirst(str_replace('_', ' ', $roleName))]
            );

            $role->permissions()->sync($perms);
        }

        // ----------------------------------------
        // Assign Users — each department has admin
        // ----------------------------------------
        $demoUsers = [
            'admin@example.com'             => 'admin',
            'product.admin@example.com'     => 'product_manager',
            'order.admin@example.com'       => 'order_manager',
            'category.admin@example.com'    => 'category_manager',
            'banner.admin@example.com'      => 'banner_manager',
            'coupon.admin@example.com'      => 'coupon_manager',
            'payment.admin@example.com'     => 'payment_manager',
        ];

        foreach ($demoUsers as $email => $roleName) {

            $first = ucfirst(str_replace('.', ' ', explode('@', $email)[0]));

            $user = User::updateOrCreate(
                ['email' => $email],
                [
                    'first_name' => $first,
                    'last_name' => 'Admin',
                    'password' =>'password123',
                    'is_admin' => true,
                    'is_active' => true
                ]
            );

            $role = Role::where('name', $roleName)->first();
            $user->role()->associate($role);
            $user->save();
        }

        echo "Roles & Permissions Seeder completed successfully!\n";
    }
}
