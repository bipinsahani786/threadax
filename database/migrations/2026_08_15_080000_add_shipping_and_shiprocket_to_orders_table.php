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
        Schema::table('orders', function (Blueprint $table) {
            $table->string('courier_name')->nullable()->after('payment_status');
            $table->string('tracking_number')->nullable()->after('courier_name');
            $table->string('tracking_url')->nullable()->after('tracking_number');
            $table->string('shiprocket_order_id')->nullable()->after('tracking_url');
            $table->string('shiprocket_shipment_id')->nullable()->after('shiprocket_order_id');
            $table->string('shiprocket_awb_code')->nullable()->after('shiprocket_shipment_id');
            $table->string('shiprocket_courier_name')->nullable()->after('shiprocket_awb_code');
            $table->date('estimated_delivery_date')->nullable()->after('shiprocket_courier_name');
            $table->timestamp('shipped_at')->nullable()->after('estimated_delivery_date');
            $table->timestamp('delivered_at')->nullable()->after('shipped_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'courier_name',
                'tracking_number',
                'tracking_url',
                'shiprocket_order_id',
                'shiprocket_shipment_id',
                'shiprocket_awb_code',
                'shiprocket_courier_name',
                'estimated_delivery_date',
                'shipped_at',
                'delivered_at',
            ]);
        });
    }
};
