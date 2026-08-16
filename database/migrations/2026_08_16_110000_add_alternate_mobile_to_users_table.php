<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * A second contact number — useful across every role (a Customer's
     * delivery contact if their own phone is unreachable, a Store/Captain's
     * backup line), so it lives on `users` generically rather than being
     * Customer-only. Nullable and unconstrained (no `unique`, unlike
     * `mobile`) since it's just a fallback contact, not a login identifier.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('alternate_mobile')->nullable()->after('mobile');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('alternate_mobile');
        });
    }
};
