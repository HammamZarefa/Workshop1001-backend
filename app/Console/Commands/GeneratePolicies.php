<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class GeneratePolicies extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:policies';


    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate all system policies';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $policies = [
            'RolePolicy' => 'Role',
            'PermissionPolicy' => 'Permission',
            'UserPolicy' => 'User',
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
