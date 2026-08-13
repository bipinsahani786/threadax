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
        Schema::table('addresses', function (Blueprint $table) {
            $table->renameColumn('street', 'line1');
            $table->string('line2')->nullable()->after('line1');
            $table->string('landmark')->nullable()->after('line2');
            $table->string('alternate_phone')->nullable()->after('phone');
            $table->boolean('is_default')->default(false)->after('type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('addresses', function (Blueprint $table) {
            $table->renameColumn('line1', 'street');
            $table->dropColumn(['line2', 'landmark', 'alternate_phone', 'is_default']);
        });
    }
};
