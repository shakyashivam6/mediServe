<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('shop_orders', function (Blueprint $table) {
            $table->string('fulfillment_status', 30)->default('pending')->after('status');
            $table->text('fulfillment_remark')->nullable()->after('fulfillment_status');
            $table->foreignId('captain_id')->nullable()->after('fulfillment_remark')->constrained('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('shop_orders', function (Blueprint $table) {
            $table->dropConstrainedForeignId('captain_id');
            $table->dropColumn(['fulfillment_status', 'fulfillment_remark']);
        });
    }
};
