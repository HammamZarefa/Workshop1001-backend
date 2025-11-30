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
                'image' => public_path('images/category1.png'),
            ],
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

            if (file_exists($item['image'])) {
                $category->addMedia($item['image'])
                    ->preservingOriginal()
                    ->toMediaCollection('icon');
            }
        }
    }
}
