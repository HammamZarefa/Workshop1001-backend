<?php

namespace Tests\Feature;

use App\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class SettingsHelperTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_set_and_get_a_string_setting()
    {
        setting_set('site_name', 'My Website');

        $this->assertEquals('My Website', setting_get('site_name'));
    }

    /** @test */
    public function it_can_set_and_get_a_number_setting()
    {
        setting_set('tax_rate', 15, 'number');

        $this->assertEquals(15, setting_get('tax_rate'));
    }

   /** @test */
public function it_can_set_and_get_a_boolean_setting()
{
    setting_set('is_active', true, 'boolean');

    $this->assertEquals('1', setting_get('is_active'));
}
/** @test */
public function it_can_set_and_get_a_json_setting()
{
    setting_set('shipping', [
        'inside_city' => 10,
        'outside_city' => 20,
    ], 'json');

    $this->assertJson(setting_get('shipping'));

    $this->assertEquals(
        ['inside_city' => 10, 'outside_city' => 20],
        json_decode(setting_get('shipping'), true)
    );
}

    /** @test */
    public function it_returns_default_value_if_setting_not_found()
    {
        $this->assertEquals('default', setting_get('unknown_key', 'default'));
    }

   /** @test */
public function cache_is_cleared_when_setting_is_updated()
{
    Cache::shouldReceive('forget')
        ->with('settings')
        ->twice();

    setting_set('currency', 'USD', 'string');
}
}
