<?php

namespace App\Http\Controllers\Captain;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * A Captain's landing page — what's still out for delivery (needs
     * action) plus a short recent-deliveries list. The full day-by-day
     * history with collection totals lives in CollectionReportController.
     */
    public function index(Request $request)
    {
        $user = $request->user();

        return view('Captain.dashboard', [
            'toDeliver' => $user->assignedPrescriptions()
                ->where('status', 'dispatched')
                ->with('customer')
                ->latest()
                ->get(),
            'recentlyDelivered' => $user->assignedPrescriptions()
                ->where('status', 'delivered')
                ->with('customer')
                ->latest('delivered_at')
                ->limit(5)
                ->get(),
        ]);
    }
}
