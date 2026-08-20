<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\Prescription;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

/**
 * A Store's own accounting view — every payment the software has ever
 * recorded against this Store's orders, in one place, regardless of which
 * screen originally captured it (Prepaid is set at Assign Captain time in
 * Store\PrescriptionController, COD moves pending -> collected -> settled
 * across Captain\DeliveryController and Store\CodSettlementController). A
 * sale is booked here the moment an order is actually `delivered` — that's
 * the revenue-recognition point, matching `delivered_at` as the ledger
 * date, not `created_at` (when it was merely uploaded) or `confirmed_at`
 * (when it was merely priced).
 */
class LedgerController extends Controller
{
    public function index(Request $request)
    {
        $storeId = $request->user()->id;

        [$from, $to] = $this->dateRange($request);

        if ($request->ajax()) {
            $rows = Prescription::query()
                ->where('store_id', $storeId)
                ->where('status', 'delivered')
                ->whereBetween('delivered_at', [$from->copy()->startOfDay(), $to->copy()->endOfDay()])
                ->with('customer');

            return DataTables::of($rows)
                ->addColumn('date', fn (Prescription $p) => $p->delivered_at->format('d M Y, h:i A'))
                ->addColumn('order', fn (Prescription $p) => $p->order_number ?? '#'.$p->id)
                ->addColumn('customer', fn (Prescription $p) => $p->customer->first_name.' '.$p->customer->second_name)
                ->addColumn('payment_mode', fn (Prescription $p) => $p->isCod() ? 'COD' : 'Prepaid')
                ->addColumn('payment_status', fn (Prescription $p) => $p->paymentStatusLabel())
                ->addColumn('amount', fn (Prescription $p) => number_format((float) $p->total_amount, 2))
                ->addColumn('actions', fn (Prescription $p) => '<a href="'.route('store.prescriptions.bill', $p).'" target="_blank" class="btn btn-soft-secondary btn-sm"><i class="ri-file-text-line"></i></a>')
                ->rawColumns(['actions'])
                ->make(true);
        }

        $ownDelivered = Prescription::where('store_id', $storeId)->where('status', 'delivered');

        return view('Store.ledger.index', [
            'from' => $from,
            'to' => $to,
            'todaySales' => (clone $ownDelivered)->whereDate('delivered_at', now()->toDateString())->sum('total_amount'),
            'monthSales' => (clone $ownDelivered)->whereBetween('delivered_at', [now()->startOfMonth(), now()->endOfMonth()])->sum('total_amount'),
            // "Received" = money the Store actually has: every Prepaid sale
            // (paid up front) plus every COD sale it has since settled with
            // the Captain. "Pending" is COD cash still out with a Captain
            // (collected) or not collected yet at all — the accounting gap
            // between booked revenue and cash in hand.
            'totalReceived' => (clone $ownDelivered)
                ->where(fn ($q) => $q->where('payment_method', 'prepaid')->orWhere('payment_status', 'settled'))
                ->sum('total_amount'),
            'totalPending' => (clone $ownDelivered)
                ->where('payment_method', 'cod')->where('payment_status', '!=', 'settled')
                ->sum('total_amount'),
            'periodCount' => (clone $ownDelivered)->whereBetween('delivered_at', [$from->copy()->startOfDay(), $to->copy()->endOfDay()])->count(),
            'periodTotal' => (clone $ownDelivered)->whereBetween('delivered_at', [$from->copy()->startOfDay(), $to->copy()->endOfDay()])->sum('total_amount'),
            'dailySummary' => (clone $ownDelivered)
                ->whereBetween('delivered_at', [$from->copy()->startOfDay(), $to->copy()->endOfDay()])
                ->selectRaw('DATE(delivered_at) as day, COUNT(*) as orders, SUM(total_amount) as total')
                ->groupBy('day')
                ->orderByDesc('day')
                ->get(),
        ]);
    }

    /**
     * `from`/`to` query params (the filter form below re-submits these),
     * defaulting to the current calendar month — the usual accounting
     * window a Store checks first.
     */
    protected function dateRange(Request $request): array
    {
        $from = $request->filled('from') ? \Illuminate\Support\Carbon::parse($request->query('from')) : now()->startOfMonth();
        $to = $request->filled('to') ? \Illuminate\Support\Carbon::parse($request->query('to')) : now();

        return [$from, $to];
    }
}
