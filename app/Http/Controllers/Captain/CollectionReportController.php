<?php

namespace App\Http\Controllers\Captain;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

/**
 * "How much cash am I holding, and what did I deliver today" — the report
 * a Captain checks before handing cash back to their Store (which then
 * settles it from Store\CodSettlementController).
 */
class CollectionReportController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $date = $request->filled('date')
            ? Carbon::parse($request->string('date')->value())
            : now();

        $deliveredOnDate = $user->assignedPrescriptions()
            ->where('status', 'delivered')
            ->whereDate('delivered_at', $date)
            ->with('customer')
            ->orderBy('delivered_at')
            ->get();

        // Cash currently in hand: collected from the customer, not yet
        // handed back to (and settled by) the Store — not scoped to the
        // selected date, since a Captain can be carrying collections from
        // several days if they haven't settled up yet.
        $cashInHand = $user->assignedPrescriptions()->where('payment_status', 'collected')->sum('total_amount');
        $totalSettled = $user->assignedPrescriptions()->where('payment_status', 'settled')->sum('total_amount');

        return view('Captain.collections.index', [
            'date' => $date,
            'deliveredOnDate' => $deliveredOnDate,
            'codCollectedOnDate' => $deliveredOnDate->where('payment_status', 'collected')->sum('total_amount')
                + $deliveredOnDate->where('payment_status', 'settled')->sum('total_amount'),
            'cashInHand' => $cashInHand,
            'totalSettled' => $totalSettled,
        ]);
    }
}
