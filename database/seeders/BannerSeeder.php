<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Banner;

class BannerSeeder extends Seeder
{
    public function run(): void
    {
        $banners = [
            [
                'title' => "REDEFINE\nYOUR STYLE",
                'subtitle' => 'The New Standard in Streetwear',
                'button_text' => 'Shop Collection',
                'link' => '/products',
                'image_path' => 'banners/hero-full.png', // Fallback if missing
                'position' => 'hero',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'title' => "HEAVYWEIGHT\nESSENTIALS",
                'subtitle' => 'Built for the streets',
                'button_text' => 'Explore Now',
                'link' => '/products?category=oversized',
                'image_path' => 'banners/banner-oversized.png',
                'position' => 'hero',
                'is_active' => true,
                'sort_order' => 2,
            ]
        ];

        foreach ($banners as $banner) {
            Banner::create($banner);
        }
    }
}
