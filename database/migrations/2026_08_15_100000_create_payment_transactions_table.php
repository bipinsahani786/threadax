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
        Schema::create('payment_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('payment_id')->nullable()->constrained()->onDelete('set null');

            $table->string('transaction_id')->nullable()->index(); // pay_xxx, order_xxx, rfn_xxx
            $table->string('gateway')->default('razorpay'); // razorpay, cod
            $table->string('event'); // order_init, payment_success, payment_failed, refund_init, refund_processed, webhook_captured
            $table->decimal('amount', 10, 2)->default(0);
            $table->string('currency', 10)->default('INR');
            $table->enum('status', ['success', 'failed', 'pending', 'refunded', 'info'])->default('info');
            $table->string('method')->nullable(); // upi, card, netbanking, wallet, cod

            $table->string('error_code')->nullable();
            $table->text('error_description')->nullable();
            $table->json('payload')->nullable(); // Raw request/response data
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();

            $table->timestamps();

            $table->index(['status', 'created_at']);
            $table->index(['gateway', 'event']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_transactions');
    }
};
