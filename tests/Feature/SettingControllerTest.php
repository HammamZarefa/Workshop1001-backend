<?php

namespace Tests\Feature\Admin;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SettingControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function adminUser()
    {
        return User::factory()->create([
            'is_admin' => true,
        ]);
    }

    /** @test */
    public function admin_can_view_settings_index()
    {
        $admin = $this->adminUser();

        Setting::create([
            'key' => 'site_name',
            'type' => 'string',
            'value' => 'My Site',
        ]);

        $response = $this->actingAs($admin)
            ->get(route('admin.settings.index'));

        $response->assertStatus(200);
        $response->assertSee('site_name');
    }

    /** @test */
    public function admin_can_view_edit_settings_page()
    {
        $admin = $this->adminUser();

        $response = $this->actingAs($admin)
            ->get(route('admin.settings.edit'));

        $response->assertStatus(200);
    }

    /** @test */
    public function admin_can_update_settings()
    {
        $admin = $this->adminUser();

        Setting::create([
            'key' => 'currency',
            'type' => 'string',
            'value' => 'USD',
        ]);

        $response = $this->actingAs($admin)
            ->put(route('admin.settings.update'), [
                'settings' => [
                    'currency' => 'EUR',
                ],
            ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('settings', [
            'key' => 'currency',
            'value' => 'EUR',
        ]);
    }
}
