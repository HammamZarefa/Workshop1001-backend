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


        $permissions = [
            // Products
            'view_products', 'create_products', 'edit_products', 'delete_products',

            // Orders
            'view_orders', 'manage_orders',

            // Categories
            'view_categories', 'create_categories', 'edit_categories', 'delete_categories',

            // Banners
            'view_banners', 'create_banners', 'edit_banners', 'delete_banners',
        ];

        foreach ($permissions as $p) {
            Permission::firstOrCreate(['name' => $p], ['label' => ucfirst(str_replace('_', ' ', $p))]);
        }

        $allPermissions = Permission::pluck('id')->toArray();


        $roles = [
            'admin' => $allPermissions,

            'manager' => Permission::whereIn('name', [
                'view_products', 'edit_products',
                'view_orders',
                'view_categories',
                'view_banners'
            ])->pluck('id')->toArray(),

            'editor' => Permission::whereIn('name', [
                'edit_products', 'edit_categories', 'edit_banners'
            ])->pluck('id')->toArray(),

            'viewer' => Permission::where('name', 'LIKE', 'view_%')->pluck('id')->toArray(),

            'order_manager' => Permission::whereIn('name', [
                'view_orders', 'manage_orders'
            ])->pluck('id')->toArray(),

            'product_manager' => Permission::whereIn('name', [
                'view_products', 'create_products', 'edit_products', 'delete_products'
            ])->pluck('id')->toArray(),

            'banner_manager' => Permission::whereIn('name', [
                'view_banners', 'create_banners', 'edit_banners', 'delete_banners'
            ])->pluck('id')->toArray(),

            'category_manager' => Permission::whereIn('name', [
                'view_categories', 'create_categories', 'edit_categories', 'delete_categories'
            ])->pluck('id')->toArray(),
        ];


        foreach ($roles as $roleName => $perms) {
            $role = Role::firstOrCreate(
                ['name' => $roleName],
                ['label' => ucfirst(str_replace('_', ' ', $roleName))]
            );

            $role->permissions()->sync($perms);
        }



        $demoUsers = [
            'admin@example.com' => 'admin',
            'ahmed.ali@example.com' => 'manager',
            'sara.mohammed@example.com' => 'category_manager',
            'omar.hassan@example.com' => 'order_manager',
            'lina.abdullah@example.com' => 'product_manager',
        ];

        foreach ($demoUsers as $email => $roleName) {

            $user = User::firstOrCreate(
                ['email' => $email],
                [
                    'name' => ucfirst(str_replace('@example.com', '', $email)),
                    'password' => Hash::make('password123'),
                ]
            );

            $role = Role::where('name', $roleName)->first();
            $user->roles()->syncWithoutDetaching([$role->id]);
        }

        echo "Seeder completed successfully!\n";
    }
}

