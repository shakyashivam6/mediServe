<?php

namespace App\Imports;

use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithUpsertColumns;
use Maatwebsite\Excel\Concerns\WithUpserts;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Validators\Failure;
use Throwable;

/**
 * Admin > Products > Import. Reads the medicine spreadsheet (see
 * database/Prescribed_medicine-sample_with-img-link_test.xlsx for the
 * expected columns) and upserts into `products` keyed by item_id, so
 * re-uploading the same or an updated sheet updates existing rows instead
 * of duplicating them.
 *
 * WithHeadingRow slugs the header row to snake_case automatically —
 * "drug name" becomes drug_name, "use of medicine" becomes
 * use_of_medicine, "item_id"/"image0".."image9" stay as-is.
 */
class MedicineImport implements SkipsEmptyRows, SkipsOnError, SkipsOnFailure, ToModel, WithBatchInserts, WithChunkReading, WithHeadingRow, WithUpsertColumns, WithUpserts, WithValidation
{
    public int $imported = 0;

    /** @var array<int, string> */
    public array $errors = [];

    /** @var array<int, string> */
    public array $failures = [];

    public function model(array $row): Model|array|null
    {
        $itemId = trim((string) ($row['item_id'] ?? ''));
        $name = trim((string) ($row['drug_name'] ?? ''));

        if ($itemId === '' || $name === '') {
            return null;
        }

        $this->imported++;

        return new Product([
            'item_id' => $itemId,
            'name' => $name,
            'composition' => $this->nullableString($row['composition'] ?? null),
            'manufacturer' => $this->nullableString($row['manufacturer'] ?? null),
            'mrp' => $this->nullableDecimal($row['mrp'] ?? null),
            'price' => $this->nullableDecimal($row['price'] ?? null),
            'packaging' => $this->nullableString($row['packaging'] ?? null),
            'uses' => $this->nullableString($row['use_of_medicine'] ?? null),
            'images' => $this->collectImages($row),
        ]);
    }

    public function rules(): array
    {
        return [
            'item_id' => ['required'],
            'drug_name' => ['required'],
            'mrp' => ['nullable', 'numeric'],
            'price' => ['nullable', 'numeric'],
        ];
    }

    public function uniqueBy(): string|array
    {
        return 'item_id';
    }

    /**
     * item_id/created_at are deliberately left out — the unique key never
     * needs updating, and created_at should stay at whenever the row was
     * first imported, not reset on every re-upload.
     */
    public function upsertColumns(): array
    {
        return ['name', 'composition', 'manufacturer', 'mrp', 'price', 'packaging', 'uses', 'images', 'updated_at'];
    }

    public function batchSize(): int
    {
        return 200;
    }

    public function chunkSize(): int
    {
        return 200;
    }

    public function onError(Throwable $e): void
    {
        $this->errors[] = $e->getMessage();
    }

    public function onFailure(Failure ...$failures): void
    {
        foreach ($failures as $failure) {
            $this->failures[] = "Row {$failure->row()}: ".implode(', ', $failure->errors());
        }
    }

    protected function nullableString($value): ?string
    {
        $value = trim((string) $value);

        return $value === '' ? null : $value;
    }

    protected function nullableDecimal($value): ?float
    {
        $value = trim((string) $value);

        return $value === '' || ! is_numeric($value) ? null : (float) $value;
    }

    /**
     * The sheet spreads image URLs across up to 10 columns (image0..image9)
     * instead of one repeating field — collect the non-empty ones into a
     * single ordered list.
     */
    protected function collectImages(array $row): array
    {
        $images = [];

        for ($i = 0; $i <= 9; $i++) {
            $url = trim((string) ($row["image{$i}"] ?? ''));

            if ($url !== '') {
                $images[] = $url;
            }
        }

        return $images;
    }
}
