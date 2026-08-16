<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\Prescription;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Same status→badge-colour mapping as Store\PrescriptionController, so
     * a status reads the same colour everywhere in the Store panel. Kept as
     * its own copy rather than shared — these two controllers have no
     * other coupling and duplicating one small array is cheaper than
     * inventing a shared home for it right now.
     */
    protected array $statusVariant = [
        'pending' => 'warning',
        'reviewing' => 'info',
        'contacted' => 'primary',
        'awaiting_confirmation' => 'primary',
        'confirmed' => 'success',
        'dispatched' => 'success',
        'delivered' => 'dark',
        'rejected' => 'danger',
    ];

    /**
     * A Store's own landing page — approval status, a couple of quick
     * stats, and links into the rest of the panel. Also flashes whether
     * there's any "running" prescription activity right now: new unclaimed
     * requests sitting in the shared queue, and this Store's own orders
     * still in progress — so a Store doesn't have to open Prescriptions
     * just to find out nothing's waiting. Below that, a HighDmin-style
     * activity snapshot (recent orders, recent customers, a status-mix
     * donut, a 14-day claim trend) — all built from this Store's own real
     * Prescription rows, not placeholder data.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $storeId = $user->id;

        $ownPrescriptions = Prescription::where('store_id', $storeId);

        return view('Store.dashboard', [
            'store' => $user->store,
            'captainCount' => $user->captains()->count(),
            'activeCaptainCount' => $user->captains()->where('isActive', true)->count(),
            'newRequestCount' => Prescription::whereNull('store_id')->count(),
            'activeOrderCount' => (clone $ownPrescriptions)
                ->whereIn('status', ['reviewing', 'contacted', 'awaiting_confirmation', 'confirmed', 'dispatched'])
                ->count(),
            'statusVariant' => $this->statusVariant,
            'totalOrderCount' => (clone $ownPrescriptions)->count(),
            'recentPrescriptions' => (clone $ownPrescriptions)->with('customer')->latest()->take(5)->get(),
            // One row per customer, most-recent order first — dedupe in PHP
            // rather than a GROUP BY so we can still show that order's full
            // status/amount, not just an id. Fine at a single store's
            // volume; would need a real query if this ever needs to scale.
            'recentCustomerOrders' => (clone $ownPrescriptions)->with('customer')->latest()->get()->unique('user_id')->take(5)->values(),
            'statusBreakdown' => $this->statusBreakdown(clone $ownPrescriptions),
            'claimTrend' => $this->claimTrend($storeId),
        ]);
    }

    /**
     * Buckets every status this Store's claimed orders can be in down to 5
     * groups for the donut chart — the raw enum has too many close-together
     * states (reviewing/contacted/awaiting_confirmation) to read at a
     * glance as separate slices.
     */
    protected function statusBreakdown($query): array
    {
        $counts = (clone $query)->selectRaw('status, COUNT(*) as cnt')->groupBy('status')->pluck('cnt', 'status');

        $inReview = ($counts['reviewing'] ?? 0) + ($counts['contacted'] ?? 0) + ($counts['awaiting_confirmation'] ?? 0);

        return [
            'In Review' => $inReview,
            'Confirmed' => $counts['confirmed'] ?? 0,
            'Out for Delivery' => $counts['dispatched'] ?? 0,
            'Delivered' => $counts['delivered'] ?? 0,
            'Rejected' => $counts['rejected'] ?? 0,
        ];
    }

    /**
     * Daily count of orders this Store claimed over the last 14 days, keyed
     * by `reviewed_at` (set the moment claim() runs) rather than
     * `created_at` — that's when this Store actually took the order on,
     * which is the trend a Store cares about on its own dashboard.
     */
    protected function claimTrend(int $storeId): array
    {
        $since = now()->subDays(13)->startOfDay();

        $counts = Prescription::where('store_id', $storeId)
            ->whereNotNull('reviewed_at')
            ->where('reviewed_at', '>=', $since)
            ->selectRaw('DATE(reviewed_at) as day, COUNT(*) as cnt')
            ->groupBy('day')
            ->pluck('cnt', 'day');

        $labels = [];
        $values = [];

        for ($i = 13; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $labels[] = $date->format('d M');
            $values[] = (int) ($counts[$date->format('Y-m-d')] ?? 0);
        }

        return ['labels' => $labels, 'values' => $values];
    }
}
