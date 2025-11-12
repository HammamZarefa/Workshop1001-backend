<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Onboarding;

class OnboardingSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            [
                'title' => 'مرحبًا بك في التطبيق',
                'subtitle' => 'تجربة تسوق مميزة',
                'description' => 'ابدأ رحلتك معنا مع أفضل العروض والخدمات المميزة.',
                'image' => public_path('images/onboarding1.png'),
            ],
            [
                'title' => 'سهولة في التصفح',
                'subtitle' => 'ابحث وتسوق بسرعة',
                'description' => 'اكتشف منتجاتك المفضلة بسهولة وراحة تامة.',
                'image' => public_path('images/onboarding2.png'),
            ],
            [
                'title' => 'طرق دفع آمنة',
                'subtitle' => 'راحة بالك أولويتنا',
                'description' => 'استمتع بخيارات دفع آمنة وسريعة تناسب الجميع.',
                'image' => public_path('images/onboarding3.png'),
            ],
        ];

        foreach ($data as $item) {
            $step = Onboarding::create([
                'title' => $item['title'],
                'subtitle' => $item['subtitle'],
                'description' => $item['description'],
            ]);

            if (file_exists($item['image'])) {
                $step->addMedia($item['image'])
                    ->preservingOriginal()
                    ->toMediaCollection('images');
            }
        }
    }
}
