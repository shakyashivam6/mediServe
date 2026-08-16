<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Adds the Captain-side delivery/COD-collection loop on top of the
     * Prescription MVP (see project memory: prescription-mvp-flow) — a new
     * migration rather than editing create_prescriptions_table directly,
     * since that one has already run and may carry live rows (matches how
     * this repo already handles post-hoc column additions elsewhere, e.g.
     * add_login_id_to_users_table).
     *
     * `payment_status` is a single linear progression rather than a
     * separate "collected" boolean + "settled" boolean, since it only ever
     * moves one way: not_required (prepaid) or pending (COD, set together
     * with payment_method at Captain assignment) -> collected (Captain got
     * the cash at the door) -> settled (Store physically received that cash
     * back from the Captain and confirmed it).
     */
    public function up(): void
    {
        Schema::table('prescriptions', function (Blueprint $table) {
            $table->enum('payment_method', ['prepaid', 'cod'])->nullable()->after('captain_id');
            $table->enum('payment_status', ['not_required', 'pending', 'collected', 'settled'])->nullable()->after('payment_method');

            $table->timestamp('delivered_at')->nullable()->after('reviewed_at');
            $table->timestamp('collected_at')->nullable()->after('delivered_at');
            $table->timestamp('settled_at')->nullable()->after('collected_at');
        });

        Schema::table('prescriptions', function (Blueprint $table) {
            // Redefine with `delivered` added, reached once the Captain
            // marks a `dispatched` order handed over.
            $table->enum('status', ['pending', 'reviewing', 'contacted', 'awaiting_confirmation', 'confirmed', 'dispatched', 'delivered', 'rejected'])
                ->default('pending')
                ->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('prescriptions', function (Blueprint $table) {
            $table->dropColumn(['payment_method', 'payment_status', 'delivered_at', 'collected_at', 'settled_at']);
        });

        Schema::table('prescriptions', function (Blueprint $table) {
            $table->enum('status', ['pending', 'reviewing', 'contacted', 'awaiting_confirmation', 'confirmed', 'dispatched', 'rejected'])
                ->default('pending')
                ->change();
        });
    }
};
