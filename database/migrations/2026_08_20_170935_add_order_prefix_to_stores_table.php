<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * A Store-configurable prefix for its own auto-generated Order IDs
     * (see add_order_number_to_prescriptions_table) — set on the Store's
     * own self-service profile/settings screen (Store\ProfileController).
     * Null means "hasn't set one yet", not "explicitly blank" — Prescription
     * ::generateOrderNumber() falls back to the platform default "OD"
     * prefix in that case rather than storing an empty prefix.
     */
    public function up(): void
    {
        Schema::table('stores', function (Blueprint $table) {
            $table->string('order_prefix', 10)->nullable()->after('gst_no');
        });
    }

    public function down(): void
    {
        Schema::table('stores', function (Blueprint $table) {
            $table->dropColumn('order_prefix');
        });
    }
};
