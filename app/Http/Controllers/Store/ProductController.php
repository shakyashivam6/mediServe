<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Read-only medicine lookup — backs the autosuggest on a Prescription's
 * "Add medicine" row (Store\PrescriptionController::update()'s items
 * table). A Store can still type any free-text medicine name that isn't in
 * this catalog (see that form's plain text input, unchanged); this just
 * makes an existing catalog entry fast to find, by name or by its 3-letter
 * Product::code, without forcing a selection.
 */
class ProductController extends Controller
{
    public function search(Request $request): JsonResponse
    {
        $q = trim((string) $request->query('q', ''));

        if (mb_strlen($q) < 2) {
            return response()->json([]);
        }

        $products = Product::query()
            ->where('is_active', true)
            ->where(fn ($query) => $query->where('name', 'like', "%{$q}%")->orWhere('code', 'like', strtoupper($q).'%'))
            // An exact code hit is almost certainly what was meant (codes
            // are only 3 letters, typed on purpose) — rank it first, then
            // a name starting with the query, then any other substring
            // match.
            ->orderByRaw('CASE WHEN code = ? THEN 0 WHEN name LIKE ? THEN 1 ELSE 2 END', [strtoupper($q), "{$q}%"])
            ->limit(10)
            ->get(['id', 'code', 'name', 'mrp']);

        return response()->json($products);
    }
}
