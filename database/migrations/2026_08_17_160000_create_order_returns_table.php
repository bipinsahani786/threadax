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
        Schema::create('order_returns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('return_number')->unique();
            $table->enum('type', ['return', 'exchange'])->default('return'); // return = refund, exchange = replacement
            $table->string('status')->default('requested'); 
            // requested, approved, rejected, pickup_scheduled, picked_up, received_at_hub, refunded, exchanged, completed, cancelled
            
            $table->string('reason');
            $table->text('customer_notes')->nullable();
            
            // Refund Details (For Returns)
            $table->string('refund_mode')->default('original_source'); // original_source, upi, bank_transfer, store_credit
            $table->decimal('refund_amount', 10, 2)->default(0);
            $table->string('upi_id')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('bank_account_number')->nullable();
            $table->string('bank_ifsc')->nullable();
            $table->string('bank_beneficiary_name')->nullable();
            $table->string('refund_transaction_id')->nullable();
            
            // Reverse Pickup Details
            $table->string('pickup_courier')->nullable();
            $table->string('pickup_awb')->nullable();
            $table->date('pickup_scheduled_date')->nullable();
            
            // Admin & Operational Notes
            $table->text('admin_notes')->nullable();
            $table->text('rejection_reason')->nullable();
            
            // Milestone Timestamps
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('picked_up_at')->nullable();
            $table->timestamp('received_at')->nullable();
            $table->timestamp('refunded_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('rejected_at')->nullable();
            
            $table->timestamps();
        });

        Schema::create('order_return_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_return_id')->constrained()->cascadeOnDelete();
            $table->foreignId('order_item_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('product_variant_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('exchange_variant_id')->nullable()->constrained('product_variants')->nullOnDelete(); // New size/variant requested
            $table->integer('quantity')->default(1);
            $table->decimal('price', 10, 2)->default(0);
            $table->decimal('total', 10, 2)->default(0);
            $table->string('reason')->nullable();
            $table->string('condition')->nullable(); // e.g. unworn_with_tags, defective, etc.
            $table->boolean('is_restocked')->default(false);
            $table->timestamps();
        });

        Schema::create('order_return_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_return_id')->constrained()->cascadeOnDelete();
            $table->string('image_path');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_return_images');
        Schema::dropIfExists('order_return_items');
        Schema::dropIfExists('order_returns');
    }
};
