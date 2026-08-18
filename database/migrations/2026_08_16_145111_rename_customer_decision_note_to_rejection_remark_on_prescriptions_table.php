<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * `customer_decision_note` was only ever written by Customer::reject()
     * — it never meant anything broader ("decision note" implied it might
     * also cover accept(), which never touched it). Now that a Store can
     * also reject on the customer's behalf and record its own reason, the
     * column is renamed to what it's actually always been: the reason a
     * Prescription was rejected, authored by whichever side did it.
     *
     * Raw `CHANGE COLUMN` rather than Schema::renameColumn() — this app
     * doesn't have doctrine/dbal installed, which Laravel's schema builder
     * needs for a portable rename; MySQL's own syntax needs no such
     * dependency.
     */
    public function up(): void
    {
        DB::statement('ALTER TABLE prescriptions CHANGE customer_decision_note rejection_remark TEXT NULL');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE prescriptions CHANGE rejection_remark customer_decision_note TEXT NULL');
    }
};
