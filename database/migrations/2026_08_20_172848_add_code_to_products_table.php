<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * A short, unique 3-capital-letter code per Product — the fast-search
     * key a Store types instead of the full medicine name (see
     * Product::generateCode()). Deliberately separate from `item_id` (the
     * CSV's own long slug, e.g. "jastinib-tablet-979900") — that one only
     * exists to de-duplicate re-imports, this one is what's actually shown/
     * typed/searched.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('code', 3)->nullable()->unique()->after('item_id');
        });

        $this->backfillCodes();
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('code');
        });
    }

    /**
     * Self-contained (no App\Models\Product dependency, same reasoning as
     * the prescription_number backfill migration) — derive a 3-letter code
     * from each product's name, falling back to a full A-Z-A-Z-A-Z scan for
     * any collision. Names in this catalog cluster heavily on their first
     * few letters (e.g. a whole batch starting "Ja-"/"Je-"/"Ju-"), so a
     * naive "first 3 letters" would collide constantly — hence the ordered
     * candidate list before the exhaustive fallback.
     */
    protected function backfillCodes(): void
    {
        $used = [];
        $rows = DB::table('products')->orderBy('id')->select('id', 'name')->get();

        foreach ($rows as $row) {
            $letters = strtoupper(preg_replace('/[^A-Za-z]/', '', $row->name));
            $consonants = preg_replace('/[AEIOU]/', '', $letters);

            $candidates = [];
            if (strlen($letters) >= 3) {
                $candidates[] = substr($letters, 0, 3);
            }
            if (strlen($consonants) >= 3) {
                $candidates[] = substr($consonants, 0, 3);
            }
            if (strlen($letters) >= 3) {
                $mid = intdiv(strlen($letters), 2);
                $candidates[] = $letters[0].$letters[$mid].$letters[strlen($letters) - 1];
            }

            $code = null;

            foreach ($candidates as $candidate) {
                if (strlen($candidate) === 3 && ! isset($used[$candidate])) {
                    $code = $candidate;
                    break;
                }
            }

            if ($code === null) {
                for ($a = 65; $a <= 90 && $code === null; $a++) {
                    for ($b = 65; $b <= 90 && $code === null; $b++) {
                        for ($c = 65; $c <= 90 && $code === null; $c++) {
                            $candidate = chr($a).chr($b).chr($c);
                            if (! isset($used[$candidate])) {
                                $code = $candidate;
                            }
                        }
                    }
                }
            }

            $used[$code] = true;

            DB::table('products')->where('id', $row->id)->update(['code' => $code]);
        }
    }
};
