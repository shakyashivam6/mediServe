<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * A human-facing Order ID, distinct from the auto-increment `id` used
     * everywhere internally (routes, the Bill, foreign keys) — generated
     * once, the moment an order is actually finalised (status turns
     * `confirmed`, whichever side triggers it — see Prescription::
     * generateOrderNumber() and its two call sites in Store\
     * PrescriptionController::update() and Customer\PrescriptionController
     * ::accept()). Nullable because every order before that point (pending
     * through awaiting_confirmation) simply doesn't have one yet.
     */
    public function up(): void
    {
        Schema::table('prescriptions', function (Blueprint $table) {
            $table->string('order_number', 30)->nullable()->unique()->after('id');
        });
    }

    public function down(): void
    {
        Schema::table('prescriptions', function (Blueprint $table) {
            $table->dropColumn('order_number');
        });
    }
};
