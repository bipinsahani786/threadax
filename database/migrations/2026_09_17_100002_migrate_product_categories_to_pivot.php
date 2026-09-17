<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Step 1: Migrate existing category_id data to pivot table
        $products = DB::table('products')->whereNotNull('category_id')->get(['id', 'category_id']);

        foreach ($products as $product) {
            // Check if the category still exists before inserting
            $categoryExists = DB::table('categories')->where('id', $product->category_id)->exists();
            if ($categoryExists) {
                DB::table('category_product')->insertOrIgnore([
                    'product_id'  => $product->id,
                    'category_id' => $product->category_id,
                    'is_primary'  => true,
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ]);
            }
        }

        // Step 2: Drop the foreign key and category_id column
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropColumn('category_id');
        });
    }

    public function down(): void
    {
        // Re-add category_id column
        Schema::table('products', function (Blueprint $table) {
            $table->foreignId('category_id')->nullable()->after('id')->constrained()->onDelete('cascade');
        });

        // Migrate primary categories back
        $pivotData = DB::table('category_product')->where('is_primary', true)->get();
        foreach ($pivotData as $row) {
            DB::table('products')->where('id', $row->product_id)->update([
                'category_id' => $row->category_id,
            ]);
        }
    }
};
