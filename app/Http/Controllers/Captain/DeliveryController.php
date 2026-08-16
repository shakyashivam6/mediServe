<?php

namespace App\Http\Controllers\Captain;

use App\Http\Controllers\Controller;
use App\Models\Prescription;
use App\Notifications\PrescriptionEventNotification;
use Illuminate\Http\Request;

class DeliveryController extends Controller
{
    /**
     * Marks a dispatched order handed over. For a COD order this doubles
     * as the cash-collection step — collection genuinely happens at the
     * door alongside delivery in real life, so it's one action rather than
     * two. Leaving the "collected" checkbox unticked still marks the order
     * delivered but keeps payment_status at `pending` (e.g. the customer
     * asked to pay later) — the Captain's collection report and the
     * Store's settlement screen both key off payment_status, not delivery
     * status, so nothing gets silently marked as paid.
     */
    public function deliver(Request $request, Prescription $prescription)
    {
        abort_unless($prescription->captain_id === $request->user()->id, 404);
        abort_unless($prescription->status === 'dispatched', 422, 'This order is not out for delivery.');

        $collected = $prescription->isCod() && $request->boolean('collected');

        $prescription->update([
            'status' => 'delivered',
            'delivered_at' => now(),
            ...($collected ? ['payment_status' => 'collected', 'collected_at' => now()] : []),
        ]);

        $prescription->customer->notify(new PrescriptionEventNotification(
            $prescription,
            'Delivered',
            "Your order for prescription #{$prescription->id} has been delivered.",
            route('customer.prescriptions.show', $prescription),
            'ri-checkbox-circle-line',
            'success',
        ));

        $collectedNote = $collected ? ' — ₹'.number_format((float) $prescription->total_amount, 2).' collected in cash.' : '';
        $prescription->store?->notify(new PrescriptionEventNotification(
            $prescription,
            'Order delivered',
            "{$request->user()->first_name} delivered prescription #{$prescription->id}.".$collectedNote,
            route('store.prescriptions.show', $prescription),
            'ri-checkbox-circle-line',
            'success',
        ));

        return redirect()->route('captain.dashboard')->with('status', 'Marked delivered.');
    }
}
