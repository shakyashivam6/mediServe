<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * The in-app chat noted as "not built yet" on the Prescription MVP (see
     * project memory: prescription-mvp-flow) — text-only for now, tied to
     * one Prescription rather than a separate Order (none exists yet).
     * `user_id` is whoever sent it (the Customer, or the claiming Store's
     * account) — which side that is comes from comparing it against
     * `prescriptions.user_id`/`store_id`, not a separate role column.
     */
    public function up(): void
    {
        Schema::create('prescription_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('prescription_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->text('body');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prescription_messages');
    }
};
