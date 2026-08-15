<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('order_trackings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            $table->string('status')->default('order_placed'); // order_placed, processing, packed, shipped, in_transit, out_for_delivery, delivered, cancelled, rto
            $table->string('title'); // e.g. "Order Confirmed", "Departed Mumbai Hub", "Out for Delivery"
            $table->string('location')->nullable(); // e.g. "Mumbai Warehouse", "Delhi Hub"
            $table->text('activity')->nullable(); // Detailed notes or courier log
            $table->string('courier_name')->nullable(); // e.g. "Delhivery", "Blue Dart", "Shiprocket"
            $table->string('tracking_number')->nullable(); // AWB number
            $table->timestamp('event_time')->useCurrent();
            $table->timestamps();

            $table->index(['order_id', 'event_time']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_trackings');
    }
};
