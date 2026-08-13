<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ProductImage;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $categories = Category::whereNotNull('parent_id')->get();
        if ($categories->isEmpty()) return;

        $catMen = $categories->where('slug', 'men')->first();
        $catWomen = $categories->where('slug', 'women')->first();
        $catOversized = $categories->where('slug', 'oversized')->first();
        $catHoodies = $categories->where('slug', 'hoodies')->first();
        
        $fallbackCat = $categories->first();

        $products = [
            // Row 1
            ['name' => 'Essential Oversized Tee — Black', 'cat' => $catOversized, 'price' => 799, 'compare' => 1299, 'sku' => 'TX-OT-001-BLK'],
            ['name' => 'Essential Oversized Tee — White', 'cat' => $catOversized, 'price' => 799, 'compare' => 1299, 'sku' => 'TX-OT-001-WHT'],
            ['name' => 'Statement Hoodie — Charcoal', 'cat' => $catHoodies, 'price' => 1499, 'compare' => 2299, 'sku' => 'TX-HD-001-CHR'],
            ['name' => 'Archive Hoodie — Black', 'cat' => $catHoodies, 'price' => 1699, 'compare' => 2499, 'sku' => 'TX-HD-002-BLK'],
            
            // Row 2
            ['name' => 'Vintage Wash Tee — Grey', 'cat' => $catMen, 'price' => 899, 'compare' => 1499, 'sku' => 'TX-VT-001-GRY'],
            ['name' => 'Heavyweight Cargo Pants — Olive', 'cat' => $catMen, 'price' => 1999, 'compare' => 2999, 'sku' => 'TX-CP-001-OLV'],
            ['name' => 'Cropped Street Hoodie — Sand', 'cat' => $catWomen, 'price' => 1299, 'compare' => 1999, 'sku' => 'TX-WHD-001-SND'],
            ['name' => 'Parachute Pants — Black', 'cat' => $catWomen, 'price' => 1799, 'compare' => 2599, 'sku' => 'TX-PP-001-BLK'],
            
            // Row 3
            ['name' => 'Graphic Print Tee — Cyber', 'cat' => $catOversized, 'price' => 999, 'compare' => 1599, 'sku' => 'TX-GP-001-CYB'],
            ['name' => 'Textured Knit Sweater', 'cat' => $catMen, 'price' => 2199, 'compare' => 3499, 'sku' => 'TX-KS-001-BEG'],
            ['name' => 'Boxy Fit Jacket — Denim', 'cat' => $catMen, 'price' => 2499, 'compare' => 3999, 'sku' => 'TX-JK-001-DNM'],
            ['name' => 'Ribbed Beanie — Orange', 'cat' => $fallbackCat, 'price' => 499, 'compare' => 799, 'sku' => 'TX-AC-001-ORG'],
        ];

        foreach ($products as $i => $data) {
            $catId = $data['cat'] ? $data['cat']->id : $fallbackCat->id;
            
            $product = Product::firstOrCreate(
                ['sku' => $data['sku']],
                [
                    'name' => $data['name'],
                    'category_id' => $catId,
                    'price' => $data['price'],
                    'compare_price' => $data['compare'],
                    'short_description' => 'Premium 100% cotton apparel with flawless fit.',
                    'description' => 'Designed for the streets. Heavyweight fabric offering durability and comfort. Drop-shoulder styling with a relaxed fit.',
                    'is_active' => true,
                    'is_featured' => true,
                    'created_at' => now()->subDays(rand(1, 30)),
                ]
            );

            if ($product->variants()->count() === 0) {
                $sizes = ['S', 'M', 'L', 'XL'];
                foreach ($sizes as $size) {
                    ProductVariant::create([
                        'product_id' => $product->id,
                        'size' => $size,
                        'sku' => $data['sku'] . '-' . $size,
                        'stock' => rand(10, 50),
                    ]);
                }
            }

            if ($product->images()->count() === 0) {
                // Different dummy images based on index
                $images = [
                    'https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?w=600&q=80',
                    'https://images.unsplash.com/photo-1583743814966-8936f5b7be1a?w=600&q=80',
                    'https://images.unsplash.com/photo-1556821840-3a63f95609a7?w=600&q=80',
                    'https://images.unsplash.com/photo-1618354691373-d851c5c3a990?w=600&q=80',
                    'https://images.unsplash.com/photo-1503342394128-c104d54dba01?w=600&q=80',
                    'https://images.unsplash.com/photo-1489987707023-afc66b57fb40?w=600&q=80',
                ];
                
                ProductImage::create([
                    'product_id' => $product->id,
                    'url' => $images[$i % count($images)],
                    'alt_text' => $product->name,
                    'is_primary' => true,
                    'sort_order' => 0,
                ]);
            }
        }
    }
}
