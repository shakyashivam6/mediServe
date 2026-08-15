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
        // Missing from the DB: the original create_users_table migration was
        // edited to add this column after it had already run, so it never
        // reached databases that were migrated before that edit. Adding it
        // here instead of re-editing that migration, which already applied
        // there. Guarded with hasColumn() because a *fresh* database runs
        // the already-edited create_users_table first, which now defines
        // login_id itself — without the guard this migration would try to
        // add the same column twice and fail migrate:fresh.
        if (! Schema::hasColumn('users', 'login_id')) {
            Schema::table('users', function (Blueprint $table) {
                // Nullable + unique so existing rows (no login_id yet) don't
                // collide until the seeder backfills them.
                $table->string('login_id')->nullable()->unique()->after('second_name');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('users', 'login_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('login_id');
            });
        }
    }
};
