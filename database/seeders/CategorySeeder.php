<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            [
                'title' => 'ملابس',
                'is_active' => true,
                'image_url' => 'https://images.pexels.com/photos/573130/pexels-photo-573130.jpeg',
            ],
            /*
            [
                'title' => 'أحذية',
                'is_active' => true,
                'image' => public_path('images/category2.png'),
            ],
            [
                'title' => 'إلكترونيات',
                'is_active' => true,
                'image' => public_path('images/category3.png'),
            ],
        ];

        foreach ($data as $item) {
            $category = Category::create([
                'title' => $item['title'],
                'is_active' => $item['is_active'],
            ]);

            // --- رفع الصورة بعد إنشاء الموديل ---
            if (!empty($item['image_url'])) {
                try {
                    $category
                        ->addMediaFromUrl($item['image_url'])
                        ->toMediaCollection('icon');
                } catch (\Exception $e) {
                    dump("faild" . $item['title']);
                    dump($e->getMessage());
                }
            }
        }
    }
}
