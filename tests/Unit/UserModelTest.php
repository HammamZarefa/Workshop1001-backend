<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserModelTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_factory_creates_user()
    {
        $user = User::factory()->create();

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
        ]);
    }

    /** @test */
    public function user_can_be_marked_as_admin()
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $normal = User::factory()->create(['is_admin' => false]);

        $this->assertTrue((bool) $admin->is_admin);
        $this->assertFalse((bool) $normal->is_admin);
    }
}
