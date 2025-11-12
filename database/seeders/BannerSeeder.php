<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Banner;

class BannerSeeder extends Seeder
{
    public function run(): void
    {
      //  $localPath = storage_path('app/public/seed_images');

        // Banner 1
       $banner1 = Banner::create([
    'title' => 'Banner 1',
    'description' => 'Nature trees banner',
    'link' => 'https://example.com/banner1',
    'is_active' => true,
    'sort_order' => 1,
]);

$banner1->addMediaFromUrl('https://images.pexels.com/photos/573130/pexels-photo-573130.jpeg')
    ->toMediaCollection('banners');
/*
        // Banner 2
        $banner2 = Banner::create([
            'title' => 'Banner 2',
            'description' => 'Second banner description',
            'link' => 'https://example.com/banner2',
            'is_active' => true,
            'sort_order' => 2,
        ]);
        $banner2->addMedia($localPath . '/banner2.jpg')
            ->preservingOriginal()
            ->toMediaCollection('banners');

        // Banner 3 (inactive)
        $banner3 = Banner::create([
            'title' => 'Banner 3 (inactive)',
            'description' => 'Third banner description',
            'link' => null,
            'is_active' => false,
            'sort_order' => 3,
        ]);
        $banner3->addMediaFromUrl('https://via.placeholder.com/1200x400.png?text=Banner+3')
            ->toMediaCollection('banners');
    */}
}
