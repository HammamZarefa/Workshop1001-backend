<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            [
                'title' => 'ساعة',
                'description' => 'ساعة عالية الجودة مقاومة للماء.',
                'price' => 350,
                'currency' => 'SAR',
                'stock' => 20,
                'is_active' => 1,
                'is_featured' => 1,

                'featured' => public_path('images/featured.jpg'),
                'is_special' => true,
                'colors' => json_encode(['#FF5733', '#1ABC9C']),
                'gallery' => [
                    public_path('images/gallery1.jpg'),
                    public_path('images/gallery2.jpg'),
                ],
            ],
            [
                'title' => 'حذاء رياضي',
                'description' => 'حذاء خفيف ومناسب للمشي والجري.',
                'price' => 220,
                'currency' => 'SAR',
                'stock' => 15,
                'is_active' => 1,
                'is_featured' => 0,
                'is_special' => true,
                'colors' => json_encode(['#FF5733', '#1ABC9C']),
                'featured' => public_path('images/featured2.jpg'),
                'gallery' => [
                    public_path('images/gallery3.jpg'),
                    public_path('images/gallery4.jpg'),
                ],
            ],
            [
            'title' => 'لابتوب',
            'description' => 'لابتوب حديث المواصفات',
            'price' => 3000,
            'currency' => 'SAR',
            'stock' => 10,
            'is_active' => 1,
            'is_featured' => 1,
            'featured' => public_path('images/featured3.jpg'),
            'is_special' => true,
            'colors' => json_encode(['#FF5733', '#1ABC9C']),
            'gallery' => [
                public_path('images/gallery5.jpg'),
                public_path('images/gallery6.jpg'),
            ],
          ],
        ];

        foreach ($products as $item) {

            $product = Product::create([
                'category_id' => Category::inRandomOrder()->first()->id,
                'title' => $item['title'],
                'description' => $item['description'],
                'price' => $item['price'],
                'currency' => $item['currency'],
                'stock' => $item['stock'],
                'is_active' => $item['is_active'],
                'is_featured' => $item['is_featured'],
                'is_special' => $item['is_special'],
                'colors' => $item['colors'],
            ]);

            if (file_exists($item['featured'])) {
                $product->addMedia($item['featured'])
                        ->preservingOriginal()
                        ->toMediaCollection('featured', 'public');
            }

            foreach ($item['gallery'] as $img) {
                if (file_exists($img)) {
                    $product->addMedia($img)
                            ->preservingOriginal()
                            ->toMediaCollection('gallery', 'public');

                }
            }
        }
    }
}
