<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Imports\MedicineImport;
use App\Models\Product;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

/**
 * Admin > Catalog > Products. Just the master catalog + bulk import for
 * now (roadmap Phase 2) — no manual create/edit form yet, since the
 * expected way products get here is the spreadsheet import. Requires-
 * prescription and active/inactive are the only two fields Admin sets
 * by hand, both as quick toggles from the list.
 */
class ProductController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return DataTables::of(Product::query())
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

                    return '<form method="POST" action="'.route('admin.products.toggle-rx', $product).'">'
                        .csrf_field()
                        .'<button type="submit" class="btn btn-soft-'.$variant.' btn-sm">'.$label.'</button>'
                        .'</form>';
                })
                ->addColumn('is_active', function (Product $product) {
                    $label = $product->is_active ? 'Active' : 'Inactive';
                    $variant = $product->is_active ? 'success' : 'secondary';

                    return '<form method="POST" action="'.route('admin.products.toggle-active', $product).'">'
                        .csrf_field()
                        .'<button type="submit" class="btn btn-soft-'.$variant.' btn-sm">'.$label.'</button>'
                        .'</form>';
                })
                ->rawColumns(['code', 'thumbnail', 'requires_prescription', 'is_active'])
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

    public function toggleRx(Product $product)
    {
        $product->update(['requires_prescription' => ! $product->requires_prescription]);

        return back()->with('status', "\"{$product->name}\" marked as ".($product->requires_prescription ? 'Rx (prescription required).' : 'OTC.'));
    }

    public function toggleActive(Product $product)
    {
        $product->update(['is_active' => ! $product->is_active]);

        return back()->with('status', "\"{$product->name}\" ".($product->is_active ? 'activated.' : 'deactivated.'));
    }
}
