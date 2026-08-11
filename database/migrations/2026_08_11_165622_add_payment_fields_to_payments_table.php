<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            // The actual Razorpay payment ID (pay_xxx) — needed for refunds
            $table->string('razorpay_payment_id')->nullable()->after('transaction_id');
            // Razorpay refund ID when refund is issued
            $table->string('refund_id')->nullable()->after('razorpay_payment_id');
            // Refund status tracking
            $table->enum('refund_status', ['none', 'initiated', 'processed', 'failed'])
                  ->default('none')
                  ->after('refund_id');
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn(['razorpay_payment_id', 'refund_id', 'refund_status']);
        });
    }
};
