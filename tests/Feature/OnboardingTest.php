<?php

namespace Tests\Feature;

use App\Models\Onboarding;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class OnboardingTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_fetch_all_onboardings()
    {
        Onboarding::factory()->count(3)->create();

        $response = $this->getJson('/api/v1/onboarding');

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'data' => [
                    '*' => ['id', 'title', 'subtitle', 'description', 'images']
                ]
            ]);
    }

    /** @test */
    public function user_can_fetch_single_onboarding()
    {
        $onboarding = Onboarding::factory()->create();

        $response = $this->getJson("/api/v1/onboarding/{$onboarding->id}");

        $response
            ->assertStatus(200)
           ->assertJsonStructure([
    'data' => [
        'id',
        'title',
        'subtitle',
        'description',
        'images'
    ]
]);
    }

    /** @test */
    public function admin_can_store_onboarding_with_images()
    {
        Storage::fake('public');

        $admin = User::factory()->create(['is_admin' => true]);

        $payload = [
            'title' => 'Welcome',
            'subtitle' => 'Sub text',
            'description' => 'Description here',
            'images' => [
                UploadedFile::fake()->create('img1.jpg', 200, 'image/jpeg'),
                UploadedFile::fake()->create('img1.jpg', 200, 'image/jpeg'),

            ]
        ];

        $response = $this->actingAs($admin, 'sanctum')
            ->postJson('/api/v1/onboarding', $payload);

        $response
            ->assertStatus(201)
            ->assertJsonFragment(['title' => 'Welcome']);

        $this->assertDatabaseHas('onboardings', ['title' => 'Welcome']);

        $onboarding = Onboarding::first();
        $this->assertCount(2, $onboarding->getMedia('images'));
    }

    /** @test */
    public function non_admin_cannot_store_onboarding()
    {
        $user = User::factory()->create(['is_admin' => false]);

        $payload = [
            'title' => 'Test Onboarding',
        ];

        $response = $this->actingAs($user, 'sanctum')
            ->postJson('/api/v1/onboarding', $payload);

        $response->assertStatus(403);
    }

    /** @test */
    public function admin_can_update_onboarding()
    {
        Storage::fake('public');

        $admin = User::factory()->create(['is_admin' => true]);

        $onboarding = Onboarding::factory()->create([
            'title' => 'Old Title'
        ]);

        $payload = [
            'title' => 'Updated Title',
            'images' => [
             UploadedFile::fake()->create('img1.jpg', 200, 'image/jpeg'),

            ]
        ];

        $response = $this->actingAs($admin, 'sanctum')
            ->postJson("/api/v1/onboarding/{$onboarding->id}", $payload);

        $response
            ->assertStatus(200)
            ->assertJsonFragment(['title' => 'Updated Title']);

        $this->assertDatabaseHas('onboardings', ['title' => 'Updated Title']);

        $onboarding->refresh();
        $this->assertCount(1, $onboarding->getMedia('images'));
    }

    /** @test */
    public function admin_can_delete_onboarding()
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $onboarding = Onboarding::factory()->create();

        $response = $this->actingAs($admin, 'sanctum')
            ->deleteJson("/api/v1/onboarding/{$onboarding->id}");

        $response
            ->assertStatus(200)
            ->assertJson(['message' => 'Onboarding deleted successfully']);

        $this->assertDatabaseMissing('onboardings', ['id' => $onboarding->id]);
    }

    /** @test */
    public function non_admin_cannot_delete_onboarding()
    {
        $user = User::factory()->create(['is_admin' => false]);

        $onboarding = Onboarding::factory()->create();
        $response = $this->actingAs($user, 'sanctum')
            ->deleteJson("/api/v1/onboarding/{$onboarding->id}");

        $response->assertStatus(403);
    }
}
