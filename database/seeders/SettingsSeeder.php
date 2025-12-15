<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingsSeeder extends Seeder
{

    public function run(): void
    {

    $data = [
        ['key' => 'site_name', 'type' => 'string', 'value' => 'My Shop'],
        ['key' => 'contact_email', 'type' => 'string', 'value' => 'support@example.com'],
        ['key' => 'currency', 'type' => 'string', 'value' => 'USD'],
        ['key' => 'timezone', 'type' => 'string', 'value' => 'UTC'],
        ['key' => 'tax_rate', 'type' => 'number', 'value' => '5'],
        ['key' => 'shipping_cost', 'type' => 'number', 'value' => '10'],
    ];

    foreach ($data as $item) {
        Setting::updateOrCreate(
            ['key' => $item['key']],
            $item
        );
    }
    }
}
