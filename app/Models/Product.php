<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'item_id',
    'code',
    'name',
    'composition',
    'manufacturer',
    'mrp',
    'price',
    'packaging',
    'uses',
    'images',
    'requires_prescription',
    'is_active',
])]
class Product extends Model
{
    protected function casts(): array
    {
        return [
            'mrp' => 'decimal:2',
            'price' => 'decimal:2',
            'images' => 'array',
            'requires_prescription' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    /**
     * First image from the sheet's image0..image9 columns, for list-view
     * thumbnails. Null if the row had no image URLs.
     */
    public function getThumbnailAttribute(): ?string
    {
        return $this->images[0] ?? null;
    }

    /**
     * Next unique 3-capital-letter code for a new Product — the fast-search
     * key a Store types instead of the full medicine name, distinct from
     * `item_id` (the CSV's own long slug, only ever used to de-duplicate
     * re-imports — see App\Imports\MedicineImport). Tries a handful of
     * name-derived candidates first (first 3 letters, then consonants-only,
     * then a first/middle/last spread) so the code stays at least loosely
     * mnemonic when possible, since this catalog clusters heavily on the
     * first few letters of a name (a whole batch starting "Ja-"/"Je-"/
     * "Ju-" would otherwise collide constantly on a naive first-3-letters
     * rule) — then falls back to an exhaustive AAA..ZZZ scan.
     *
     * `$avoidCodes` lets a caller processing many new rows in one pass
     * (e.g. MedicineImport, working through a batch before any of it is
     * actually written to the DB) reserve codes as it goes, so two new
     * rows in the same batch never generate the same code before either
     * exists in the database yet to be caught by the uniqueness check.
     *
     * @param  array<string, bool>  $avoidCodes  keyed by code, checked with isset()
     */
    public static function generateCode(string $name, array $avoidCodes = []): string
    {
        $letters = strtoupper(preg_replace('/[^A-Za-z]/', '', $name));
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

        $isTaken = fn (string $candidate) => isset($avoidCodes[$candidate])
            || static::query()->where('code', $candidate)->exists();

        foreach (array_unique($candidates) as $candidate) {
            if (strlen($candidate) === 3 && ! $isTaken($candidate)) {
                return $candidate;
            }
        }

        for ($a = 65; $a <= 90; $a++) {
            for ($b = 65; $b <= 90; $b++) {
                for ($c = 65; $c <= 90; $c++) {
                    $candidate = chr($a).chr($b).chr($c);
                    if (! $isTaken($candidate)) {
                        return $candidate;
                    }
                }
            }
        }

        throw new \RuntimeException('No unique 3-letter product code available — catalog has reached the 17,576-code limit.');
    }
}
