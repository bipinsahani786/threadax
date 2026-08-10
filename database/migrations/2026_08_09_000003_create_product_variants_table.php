<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->string('size')->nullable();   // S, M, L, XL, 28, 30, 7, 8, etc.
            $table->string('color')->nullable();  // Black, White, Red, etc.
            $table->string('color_hex')->nullable(); // #000000 for color swatches
            $table->string('sku')->unique()->nullable();
            $table->decimal('price', 10, 2)->nullable(); // Override product price if different
            $table->integer('stock')->default(0);
            $table->integer('low_stock_threshold')->default(5);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_variants');
    }
};
