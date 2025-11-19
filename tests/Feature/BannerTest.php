<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Banner;
use App\Models\User;



class BannerTest extends TestCase
{
use RefreshDatabase;

    public function test_can_fetch_active_banners()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');
        
     Banner::factory()->count(3)->create(['is_active' => true]);
     Banner::factory()->count(2)->create(['is_active' => false]);

        $response = $this->getJson('/api/v1/getActiveBanners');

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data');
    }
}
