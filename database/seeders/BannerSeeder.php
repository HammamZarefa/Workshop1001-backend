<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Banner;

class BannerSeeder extends Seeder
{
    public function run(): void
    {
        Banner::create([
            'title' => 'Banner 1',
            'description' => 'First banner description',
            'link' => 'https://example.com/banner1',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        Banner::create([
            'title' => 'Banner 2',
            'description' => 'Second banner description',
            'link' => 'https://example.com/banner2',
            'is_active' => true,
            'sort_order' => 2,
        ]);

        Banner::create([
            'title' => 'Banner 3 (inactive)',
            'description' => 'Third banner description',
            'link' => null,
            'is_active' => false,
            'sort_order' => 3,
        ]);
    }
}
