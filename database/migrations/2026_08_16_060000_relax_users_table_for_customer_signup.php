<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Customer self-registration (see project memory: customer-auth-and-
     * admin-driven-signup) only collects name + mobile — every field below
     * was designed for Admin-driven Store/Captain KYC and doesn't apply to
     * a Customer. Loosening to nullable rather than stuffing in fake
     * placeholder values (a fake Aadhaar/PAN would be worse than none).
     * Admin/Store/Captain creation flows are untouched — they still supply
     * every one of these fields, this just stops the DB demanding it.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('second_name')->nullable()->change();
            $table->string('adhaar')->nullable()->change();
            $table->string('pan')->nullable()->change();
            $table->string('email')->nullable()->change();
            $table->enum('gender', ['male', 'female', 'other'])->nullable()->change();
            $table->date('dob')->nullable()->change();
            $table->integer('city')->nullable()->change();
            $table->integer('state')->nullable()->change();
            $table->integer('pincode')->nullable()->change();
        });

        Schema::table('users', function (Blueprint $table) {
            // The existing `otp` column just holds the current code with no
            // expiry tracking — Customer OTP login needs one.
            $table->timestamp('otp_expires_at')->nullable()->after('otp');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('otp_expires_at');
            // Deliberately not re-tightening the columns back to NOT NULL —
            // by the time this would roll back, real nullable Customer rows
            // may already exist and that would break them.
        });
    }
};
