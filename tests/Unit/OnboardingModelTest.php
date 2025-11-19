<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Onboarding;
use Illuminate\Foundation\Testing\RefreshDatabase;

class OnboardingModelTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_be_created_via_factory()
    {
        $onboarding = Onboarding::factory()->create();

        $this->assertDatabaseHas('onboardings', [
            'id' => $onboarding->id,
            'title' => $onboarding->title,
        ]);
    }

    /** @test */
    public function it_has_expected_fillable_fields()
    {
        $onboarding = new Onboarding();

        $this->assertEqualsCanonicalizing(
            ['title', 'subtitle', 'description'],
            $onboarding->getFillable()
        );
    }

  
}
