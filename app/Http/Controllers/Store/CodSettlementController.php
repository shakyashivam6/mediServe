<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\Prescription;
use App\Models\User;
use App\Notifications\PrescriptionEventNotification;
use Illuminate\Http\Request;

/**
 * The Store's side of the cash hand-off loop: a Captain collects COD cash
 * at the door (see Captain\DeliveryController), physically carries it back,
 * and once the Store has it in hand they "settle" it here — clearing it off
 * the Captain's outstanding balance. Settlement is per-Captain and all-at-
 * once (a lump cash hand-off covering every delivery since the last
 * settlement), not itemised per order, matching how cash actually changes
 * hands.
 */
class CodSettlementController extends Controller
{
    public function index(Request $request)
    {
        $storeId = $request->user()->id;

        $pendingByCaptain = Prescription::query()
            ->where('store_id', $storeId)
            ->where('payment_status', 'collected')
            ->with('captain')
            ->get()
            ->groupBy('captain_id');

        $settledRecently = Prescription::query()
            ->where('store_id', $storeId)
            ->where('payment_status', 'settled')
            ->with('captain')
            ->latest('settled_at')
            ->limit(20)
            ->get();

        return view('Store.settlements.index', [
            'pendingByCaptain' => $pendingByCaptain,
            'settledRecently' => $settledRecently,
        ]);
    }

    /**
     * Marks every currently-`collected` order for this Captain (under this
     * Store) as `settled` in one action.
     */
    public function settle(Request $request, User $captain)
    {
        abort_unless($captain->store_id === $request->user()->id, 403);

        $pending = Prescription::query()
            ->where('store_id', $request->user()->id)
            ->where('captain_id', $captain->id)
            ->where('payment_status', 'collected')
            ->get();

        // Not an error worth a hard abort — a double-click or a second
        // browser tab landing here after someone else already settled it
        // is a normal race, not a broken request.
        if ($pending->isEmpty()) {
            return redirect()->route('store.settlements.index')
                ->with('status', "Nothing was pending settlement for {$captain->first_name} — it may have already been settled.");
        }

        $total = $pending->sum('total_amount');
        $count = $pending->count();

        Prescription::query()
            ->whereIn('id', $pending->pluck('id'))
            ->update(['payment_status' => 'settled', 'settled_at' => now()]);

        $captain->notify(new PrescriptionEventNotification(
            $pending->first(),
            'COD settlement recorded',
            "{$request->user()->store?->shop_name} marked ₹".number_format((float) $total, 2)." ({$count} order".($count === 1 ? '' : 's').') as settled.',
            route('captain.collections.index', absolute: false),
            'ri-hand-coin-line',
            'success',
        ));

        return redirect()->route('store.settlements.index')
            ->with('status', "Settled {$count} order(s) from {$captain->first_name}.");
    }
}
