<?php

namespace App\Http\Controllers;

use App\Models\Prescription;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * One shared bill/invoice PDF, reused by all three parties on a delivery —
 * Store, Captain and Customer (see routes/web.php: store.prescriptions.bill
 * / captain.deliveries.bill / customer.prescriptions.bill all point here,
 * same pattern as the shared NotificationController). Only exists once a
 * Captain has actually been assigned — that's the moment the order is a
 * real, dispatched delivery worth billing, not just an in-progress estimate.
 */
class PrescriptionBillController extends Controller
{
    public function show(Request $request, Prescription $prescription): Response
    {
        $this->authorizeView($request, $prescription);

        abort_unless($prescription->captain_id !== null, 404, 'A bill is only available once a Captain has been assigned.');

        $prescription->load('customer', 'captain', 'store.store');

        $pdf = Pdf::loadView('common.prescription-bill', ['prescription' => $prescription]);

        $filename = "MediServe-Bill-{$prescription->id}.pdf";

        // Store/Captain open it inline to check details at a glance;
        // Customer's own link asks for a forced download (see the "Download
        // Bill" button on Customer\prescriptions\show).
        return $request->boolean('download')
            ? $pdf->download($filename)
            : $pdf->stream($filename);
    }

    /**
     * Same three-way visibility rule used throughout Prescription — a Store
     * only sees its own claimed orders, a Captain only its own assigned
     * deliveries, a Customer only its own uploads.
     */
    protected function authorizeView(Request $request, Prescription $prescription): void
    {
        $allowed = match ($request->user()->role) {
            'store' => $prescription->store_id === $request->user()->id,
            'captain' => $prescription->captain_id === $request->user()->id,
            'customer' => $prescription->user_id === $request->user()->id,
            default => false,
        };

        abort_unless($allowed, 403);
    }
}
