<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * A stable, human-searchable ID for every Prescription — set the moment
     * it's uploaded (Customer\PrescriptionController::store(), see
     * Prescription::generatePrescriptionNumber()), unlike order_number
     * (add_order_number_to_prescriptions_table) which only exists once an
     * order is actually confirmed. This is what a Store/Captain/Customer
     * types into a listing's search box to jump straight to one record —
     * the internal auto-increment `id` still drives routes/relations, this
     * is purely the human-facing label.
     *
     * Always "RX-" prefixed, platform-wide (not Store-branded like
     * order_number) — a Prescription can exist before any Store has
     * claimed it, so there's no Store to derive a prefix from yet.
     */
    public function up(): void
    {
        Schema::table('prescriptions', function (Blueprint $table) {
            $table->string('prescription_number', 20)->nullable()->unique()->after('id');
        });

        // Backfill every row that predates this column, oldest first, so
        // existing prescriptions get a findable ID too rather than sitting
        // blank forever. A plain sequential counter (not chunkById's
        // per-chunk index, which would restart at 0 every 200 rows and
        // collide with the unique constraint).
        $ids = DB::table('prescriptions')->orderBy('id')->pluck('id');

        foreach ($ids as $i => $id) {
            DB::table('prescriptions')->where('id', $id)->update([
                'prescription_number' => 'RX-'.str_pad((string) ($i + 1), 6, '0', STR_PAD_LEFT),
            ]);
        }
    }

    public function down(): void
    {
        Schema::table('prescriptions', function (Blueprint $table) {
            $table->dropColumn('prescription_number');
        });
    }
};
