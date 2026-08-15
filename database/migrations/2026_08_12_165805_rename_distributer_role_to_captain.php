<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * "Distributer" is renamed to "Captain" everywhere. On a fresh database
     * the base create_users_table migration already defines the enum with
     * `captain` and there's no `distributer` data to move — this is then a
     * no-op. On a database that ran the old enum, MySQL enums can't be
     * changed piecemeal, so we widen to include both values, move the
     * data, then narrow to the final set.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['admin', 'store', 'customer', 'distributer', 'captain'])->change();
        });

        DB::table('users')->where('role', 'distributer')->update(['role' => 'captain']);

        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['admin', 'store', 'customer', 'captain'])->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['admin', 'store', 'customer', 'distributer', 'captain'])->change();
        });

        DB::table('users')->where('role', 'captain')->update(['role' => 'distributer']);

        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['admin', 'store', 'customer', 'distributer'])->change();
        });
    }
};
