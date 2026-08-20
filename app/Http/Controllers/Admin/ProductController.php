<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Imports\MedicineImport;
use App\Models\Product;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

/**
 * Admin > Catalog > Products. Master catalog + bulk import (roadmap Phase
 * 2) plus a manual Edit form for one-off corrections between imports —
 * bulk spreadsheet import is still the expected way products *arrive*
 * here, this is just for fixing up a single row afterward. Rx/Active stay
 * quick one-click toggles from the list, now AJAX (see toggleRx/
 * toggleActive) so flipping one doesn't reload the whole DataTable page/
 * lose its current filter+search+page state.
 */
class ProductController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Product::query()
                // Filter dropdowns above the table (status/rx) — sent as
                // extra ajax.data on every DataTables request, see
                // Admin.products.index's script block.
                ->when($request->filled('status'), fn ($q) => $q->where('is_active', $request->query('status') === 'active'))
                ->when($request->filled('rx'), fn ($q) => $q->where('requires_prescription', $request->query('rx') === 'rx'));

            return DataTables::of($query)
                // Yajra's own row-index column — a plain 1, 2, 3... display
                // number reflecting current sort/page, not the (gappy, DB-
                // internal) auto-increment `id`.
                ->addIndexColumn()
                ->addColumn('code', fn (Product $product) => $product->code
                    ? '<span class="badge bg-soft-primary text-primary">'.e($product->code).'</span>'
                    : '<span class="text-muted">—</span>')
                ->addColumn('thumbnail', fn (Product $product) => $product->thumbnail
                    ? '<img src="'.e($product->thumbnail).'" alt="" style="width:36px;height:36px;object-fit:cover;border-radius:4px;">'
                    : '<span class="text-muted">—</span>')
                ->addColumn('mrp', fn (Product $product) => $product->mrp !== null ? '₹'.number_format((float) $product->mrp, 2) : '—')
                ->addColumn('price', fn (Product $product) => $product->price !== null ? '₹'.number_format((float) $product->price, 2) : '—')
                ->addColumn('requires_prescription', function (Product $product) {
                    $label = $product->requires_prescription ? 'Rx' : 'OTC';
                    $variant = $product->requires_prescription ? 'danger' : 'secondary';

                    return '<button type="button" class="btn btn-soft-'.$variant.' btn-sm toggle-rx-btn" data-url="'.route('admin.products.toggle-rx', $product).'">'.$label.'</button>';
                })
                ->addColumn('is_active', function (Product $product) {
                    $label = $product->is_active ? 'Active' : 'Inactive';
                    $variant = $product->is_active ? 'success' : 'secondary';

                    return '<button type="button" class="btn btn-soft-'.$variant.' btn-sm toggle-active-btn" data-url="'.route('admin.products.toggle-active', $product).'">'.$label.'</button>';
                })
                ->addColumn('actions', fn (Product $product) => '<a href="'.route('admin.products.edit', $product).'" class="btn btn-soft-primary btn-sm"><i class="ri-pencil-line"></i></a>')
                ->rawColumns(['code', 'thumbnail', 'requires_prescription', 'is_active', 'actions'])
                ->make(true);
        }

        return view('Admin.products.index', [
            'productCount' => Product::query()->count(),
        ]);
    }

    public function import()
    {
        return view('Admin.products.import');
    }

    public function processImport(Request $request)
    {
        $request->validate([
            'file' => ['required', 'file', 'mimes:xlsx,xls,csv', 'max:20480'],
        ]);

        $import = new MedicineImport;

        Excel::import($import, $request->file('file'));

        $status = "{$import->imported} product(s) imported/updated.";

        if ($import->failures || $import->errors) {
            $problems = count($import->failures) + count($import->errors);
            $status .= " {$problems} row(s) skipped — check the file's columns match the expected format.";
        }

        return redirect()->route('admin.products.index')->with('status', $status);
    }

    /**
     * One-off manual correction — `item_id` (import de-dup key) and `code`
     * (auto-generated fast-search code, see Product::generateCode()) are
     * deliberately not editable here: item_id must stay whatever the
     * source sheet says so a re-import still matches this row, and the
     * code is meant to stay stable once assigned rather than shift every
     * time someone tweaks the name.
     */
    public function edit(Product $product)
    {
        return view('Admin.products.edit', ['product' => $product]);
    }

    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:191'],
            'composition' => ['nullable', 'string'],
            'manufacturer' => ['nullable', 'string', 'max:191'],
            'mrp' => ['nullable', 'numeric', 'min:0'],
            'price' => ['nullable', 'numeric', 'min:0'],
            'packaging' => ['nullable', 'string', 'max:191'],
            'uses' => ['nullable', 'string'],
            'requires_prescription' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $product->update([
            'name' => $data['name'],
            'composition' => $data['composition'] ?? null,
            'manufacturer' => $data['manufacturer'] ?? null,
            'mrp' => $data['mrp'] ?? null,
            'price' => $data['price'] ?? null,
            'packaging' => $data['packaging'] ?? null,
            'uses' => $data['uses'] ?? null,
            'requires_prescription' => $request->boolean('requires_prescription'),
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('admin.products.index')->with('status', "\"{$product->name}\" updated.");
    }

    /**
     * Both toggles now answer AJAX (the DataTable's own buttons, see
     * index()) with JSON instead of a redirect — a plain `back()` would
     * reload the whole page and drop the DataTable's current filter/
     * search/page state. `wantsJson()` keeps a non-JS form submission (or
     * a stale cached page) working too, falling back to the old redirect.
     */
    public function toggleRx(Request $request, Product $product)
    {
        $product->update(['requires_prescription' => ! $product->requires_prescription]);

        $status = "\"{$product->name}\" marked as ".($product->requires_prescription ? 'Rx (prescription required).' : 'OTC.');

        if ($request->wantsJson()) {
            return response()->json(['status' => $status, 'requires_prescription' => $product->requires_prescription]);
        }

        return back()->with('status', $status);
    }

    public function toggleActive(Request $request, Product $product)
    {
        $product->update(['is_active' => ! $product->is_active]);

        $status = "\"{$product->name}\" ".($product->is_active ? 'activated.' : 'deactivated.');

        if ($request->wantsJson()) {
            return response()->json(['status' => $status, 'is_active' => $product->is_active]);
        }

        return back()->with('status', $status);
    }
}
