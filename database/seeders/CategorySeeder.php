<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        // Root Categories
        $clothing = Category::firstOrCreate(['slug' => 'clothing'], [
            'name'       => 'Clothing',
            'description' => 'Premium streetwear clothing',
            'is_active'  => true,
            'show_in_header' => false,
            'sort_order' => 1,
        ]);

        $footwear = Category::firstOrCreate(['slug' => 'footwear'], [
            'name'       => 'Footwear',
            'description' => 'Sneakers and shoes',
            'is_active'  => true,
            'show_in_header' => false,
            'sort_order' => 2,
        ]);

        $accessories = Category::firstOrCreate(['slug' => 'accessories'], [
            'name'       => 'Accessories',
            'description' => 'Caps, bags, and more',
            'is_active'  => true,
            'show_in_header' => false,
            'sort_order' => 3,
        ]);

        // Header Categories
        Category::firstOrCreate(['slug' => 'men'], [
            'name'      => 'Men',
            'parent_id' => $clothing->id,
            'is_active' => true,
            'show_in_header' => true,
            'sort_order' => 1,
        ]);

        Category::firstOrCreate(['slug' => 'women'], [
            'name'      => 'Women',
            'parent_id' => $clothing->id,
            'is_active' => true,
            'show_in_header' => true,
            'sort_order' => 2,
        ]);

        Category::firstOrCreate(['slug' => 'oversized'], [
            'name'      => 'Oversized',
            'parent_id' => $clothing->id,
            'is_active' => true,
            'show_in_header' => true,
            'sort_order' => 3,
        ]);

        Category::firstOrCreate(['slug' => 'hoodies'], [
            'name'      => 'Hoodies',
            'parent_id' => $clothing->id,
            'is_active' => true,
            'show_in_header' => true,
            'sort_order' => 4,
        ]);
        
        Category::firstOrCreate(['slug' => 'accessories-headwear'], [
            'name'      => 'Headwear',
            'parent_id' => $accessories->id,
            'is_active' => true,
            'show_in_header' => true,
            'sort_order' => 5,
        ]);
    }
}
