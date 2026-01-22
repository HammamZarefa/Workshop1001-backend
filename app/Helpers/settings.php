<?php


use App\Models\Setting;
use Illuminate\Support\Facades\Cache;


if (! function_exists('setting_get')) {
    function setting_get(string $key, $default = null)
    {
        $settings = Cache::rememberForever('settings', function () {
            return Setting::all()->keyBy('key');
        });

        return $settings[$key]->parsed_value ?? $default;
    }
}

if (! function_exists('setting_set')) {
    function setting_set(string $key, $value): void
    {
        Setting::updateOrCreate(
            ['key' => $key],
            ['value' => is_array($value) ? json_encode($value) : $value]
        );

        Cache::forget('settings');
    }
}
