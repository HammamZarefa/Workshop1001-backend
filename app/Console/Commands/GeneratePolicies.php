<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class GeneratePolicies extends Command
{
    protected $signature = 'make:policies';
    protected $description = 'Generate all system policies';

    public function handle()
    {
        $policies = [
            'UserPolicy'       => 'User',
            'RolePolicy'       => 'Role',
            'PermissionPolicy' => 'Permission',
            'ProductPolicy'    => 'Product',
            'CategoryPolicy'   => 'Category',
            'BannerPolicy'     => 'Banner',
            'CouponPolicy'     => 'Coupon',
            'OrderPolicy'      => 'Order',
            'CartPolicy'       => 'Cart',
            'PaymentPolicy'    => 'Payment',

        ];

        foreach ($policies as $policy => $model) {
            $this->call('make:policy', [
                'name' => $policy,
                '--model' => $model,
            ]);

            $this->info("Policy $policy created for model $model.");
        }

        $this->info('All policies created successfully!');
    }
}
