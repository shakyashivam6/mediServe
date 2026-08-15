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
        Schema::table('users', function (Blueprint $table) {
            // Links a Captain to the Store (another users row, role=store)
            // it delivers for. Null for every other role.
            $table->foreignId('store_id')->nullable()->after('role')->constrained('users')->nullOnDelete();

            // Captain-only. Nullable/plain string rather than an enum so
            // new vehicle types don't need a migration.
            $table->string('vehicle_type')->nullable()->after('store_id');

            // Real free-text postal address. The existing `address` integer
            // column was meant to reference a location in the `locations`
            // tree, but that tree only has locality-level entries under a
            // couple of cities (see LocationsTableSeeder) — not usable as a
            // general address field. Nothing in the app reads `address`
            // today, so it's left in place (now nullable) and superseded by
            // this column going forward.
            $table->text('address_line')->nullable()->after('address');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->integer('address')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('store_id');
            $table->dropColumn(['vehicle_type', 'address_line']);
        });
    }
};
