<?php

namespace Database\Factories;

use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AuditLogFactory extends Factory
{
    protected $model = AuditLog::class;

    public function definition(): array
    {
        return [
            'admin_id'   => User::factory()->create(['is_admin' => true])->id,
            'action'     => $this->faker->randomElement(['create', 'update', 'delete']),
            'resource'   => $this->faker->randomElement(['orders', 'users', 'payments']),
            'resource_id'=> $this->faker->numberBetween(1, 1000),
            'ip_address' => $this->faker->ipv4,
            'created_at' => now(),
        ];
    }
}
