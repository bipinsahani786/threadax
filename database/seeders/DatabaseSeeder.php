<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ProductImage;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ─── Seed Admin ────────────────────────────────────────
        Admin::firstOrCreate(
            ['email' => 'admin@threadax.com'],
            [
                'name'     => 'ThreadAX Admin',
                'password' => 'admin123',
                'role'     => 'super_admin',
            ]
        );

        // ─── Seed Categories ────────────────────────────────────
        $clothing = Category::firstOrCreate(['slug' => 'clothing'], [
            'name'       => 'Clothing',
            'description' => 'Premium streetwear clothing',
            'is_active'  => true,
            'sort_order' => 1,
        ]);

        $footwear = Category::firstOrCreate(['slug' => 'footwear'], [
            'name'       => 'Footwear',
            'description' => 'Sneakers and shoes',
            'is_active'  => true,
            'sort_order' => 2,
        ]);

        $accessories = Category::firstOrCreate(['slug' => 'accessories'], [
            'name'       => 'Accessories',
            'description' => 'Caps, bags, and more',
            'is_active'  => true,
            'sort_order' => 3,
        ]);

        // Sub-categories
        $tshirts = Category::firstOrCreate(['slug' => 'oversized-tshirts'], [
            'name'      => 'Oversized T-Shirts',
            'parent_id' => $clothing->id,
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $hoodies = Category::firstOrCreate(['slug' => 'hoodies'], [
            'name'      => 'Hoodies',
            'parent_id' => $clothing->id,
            'is_active' => true,
            'sort_order' => 2,
        ]);

        // ─── Seed Sample Products ────────────────────────────────
        $products = [
            [
                'name'             => 'Essential Oversized Tee — Black',
                'category_id'      => $tshirts->id,
                'price'            => 799,
                'compare_price'    => 1299,
                'short_description' => '100% cotton premium oversized fit.',
                'description'      => 'The staple of every streetwear wardrobe. Drop-shoulder fit, GSM 220 cotton, minimal logo.',
                'is_active'        => true,
                'is_featured'      => true,
                'sku'              => 'TX-OT-001-BLK',
                'meta_title'       => 'Essential Oversized Tee Black — ThreadAX',
            ],
            [
                'name'             => 'Essential Oversized Tee — White',
                'category_id'      => $tshirts->id,
                'price'            => 799,
                'compare_price'    => 1299,
                'short_description' => '100% cotton premium oversized fit.',
                'description'      => 'The clean slate. Perfect for layering or wearing solo.',
                'is_active'        => true,
                'is_featured'      => true,
                'sku'              => 'TX-OT-001-WHT',
            ],
            [
                'name'             => 'Statement Hoodie — Charcoal',
                'category_id'      => $hoodies->id,
                'price'            => 1499,
                'compare_price'    => 2299,
                'short_description' => 'Heavy-weight 380 GSM hoodie.',
                'description'      => 'Designed for the streets, built for the cold. Premium fleece interior.',
                'is_active'        => true,
                'is_featured'      => true,
                'sku'              => 'TX-HD-001-CHR',
            ],
            [
                'name'             => 'Archive Hoodie — Black',
                'category_id'      => $hoodies->id,
                'price'            => 1699,
                'compare_price'    => 2499,
                'short_description' => 'Limited edition archive drop.',
                'description'      => 'From the ThreadAX archive. Limited units available.',
                'is_active'        => true,
                'is_featured'      => true,
                'sku'              => 'TX-HD-002-BLK',
            ],
        ];

        foreach ($products as $productData) {
            $product = Product::firstOrCreate(
                ['sku' => $productData['sku']],
                $productData
            );

            // Add variants if none exist
            if ($product->variants()->count() === 0) {
                $sizes = ['S', 'M', 'L', 'XL', 'XXL'];
                foreach ($sizes as $i => $size) {
                    ProductVariant::create([
                        'product_id' => $product->id,
                        'size'       => $size,
                        'sku'        => $productData['sku'] . '-' . $size,
                        'stock'      => rand(5, 50),
                    ]);
                }
            }

            // Add placeholder image if none exist
            if ($product->images()->count() === 0) {
                ProductImage::create([
                    'product_id' => $product->id,
                    'url'        => 'https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?w=600&q=80',
                    'alt_text'   => $product->name,
                    'is_primary' => true,
                    'sort_order' => 0,
                ]);
            }
        }

        $this->command->info('✅ Admin, Categories, and Sample Products seeded successfully!');
        $this->command->info('   Admin: admin@threadax.com / admin123');
    }
}
