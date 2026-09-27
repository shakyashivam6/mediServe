<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('shop_orders', function (Blueprint $table) {
            $table->foreignId('customer_address_id')->nullable()->after('user_id')->constrained('customer_addresses')->nullOnDelete();
            $table->string('cashfree_order_id')->nullable()->unique()->after('status');
            $table->text('cashfree_payment_session_id')->nullable()->after('cashfree_order_id');
        });
    }

    public function down(): void
    {
        Schema::table('shop_orders', function (Blueprint $table) {
            $table->dropConstrainedForeignId('customer_address_id');
            $table->dropUnique(['cashfree_order_id']);
            $table->dropColumn(['cashfree_order_id', 'cashfree_payment_session_id']);
        });
    }
};
