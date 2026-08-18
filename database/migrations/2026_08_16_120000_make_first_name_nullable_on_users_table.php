<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Customer OTP signup now only collects a mobile number up front (see
     * project memory: customer-otp-profile-completion) — name moved to the
     * mandatory post-verify profile step, so a bare row has to be creatable
     * before a name exists. Same "nullable, not fake data" approach as the
     * sibling relax migration.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('first_name')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Deliberately not re-tightening to NOT NULL — by the time this
        // would roll back, real nullable Customer rows may already exist.
    }
};
