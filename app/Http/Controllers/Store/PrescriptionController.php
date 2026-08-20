<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\Prescription;
use App\Notifications\PrescriptionEventNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

/**
 * Shared-queue routing (see project memory / the create_prescriptions_table
 * migration) — every Prescription starts unclaimed (store_id null) and any
 * approved Store can open + claim it. Once claimed, only that Store can act
 * on it; a Store still sees it in the list either way (unclaimed, or
 * claimed by itself) so it can tell what's new vs. what it's already on.
 */
class PrescriptionController extends Controller
{
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

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $storeId = $request->user()->id;

            return DataTables::of(
                Prescription::query()
                    ->with('customer')
                    ->where(fn ($q) => $q->whereNull('store_id')->orWhere('store_id', $storeId))
            )
                ->addColumn('prescription_number', fn (Prescription $p) => $p->prescription_number ?? '—')
                ->addColumn('customer', fn (Prescription $p) => $p->customer->first_name.' '.$p->customer->second_name)
                ->addColumn('mobile', fn (Prescription $p) => $p->customer->mobile)
                ->addColumn('uploaded', fn (Prescription $p) => $p->created_at->format('d M Y, h:i A'))
                ->addColumn('status', function (Prescription $p) {
                    $label = $p->store_id === null ? 'New — unclaimed' : ucfirst(str_replace('_', ' ', $p->status));
                    $badge = '<span class="badge bg-'.$this->statusVariant[$p->status].'">'.$label.'</span>';

                    // Flash a COD amount still owed at a glance, without
                    // opening each row — the same "pending" wording the
                    // Customer and Captain sides use.
                    if ($p->isCodAmountPending()) {
                        $badge .= ' <span class="badge bg-danger">COD ₹'.number_format((float) $p->total_amount, 2).' pending</span>';
                    } elseif ($p->isCodAwaitingSettlement()) {
                        $badge .= ' <span class="badge bg-warning">Awaiting settlement</span>';
                    }

                    return $badge;
                })
                ->addColumn('actions', fn (Prescription $p) => '<a href="'.route('store.prescriptions.show', $p).'" class="btn btn-soft-primary btn-sm"><i class="ri-eye-line"></i> Open</a>')
                ->rawColumns(['status', 'actions'])
                ->make(true);
        }

        return view('Store.prescriptions.index');
    }

    public function show(Request $request, Prescription $prescription)
    {
        $this->authorizeVisible($request, $prescription);

        $prescription->load('customer', 'captain');

        return view('Store.prescriptions.show', [
            'prescription' => $prescription,
            'captains' => $request->user()->captains()->where('isActive', true)->get(),
            'distanceKm' => $prescription->distanceFromStoreKm(),
            'statusVariant' => $this->statusVariant,
        ]);
    }

    /**
     * First Store to open an unclaimed Prescription owns it from here on.
     */
    public function claim(Request $request, Prescription $prescription)
    {
        abort_unless($prescription->store_id === null, 403, 'This prescription has already been claimed by another store.');

        $prescription->update([
            'store_id' => $request->user()->id,
            'status' => 'reviewing',
            'reviewed_at' => now(),
        ]);

        $shopName = $request->user()->store?->shop_name ?? 'A store';
        $prescription->customer->notify(new PrescriptionEventNotification(
            $prescription,
            'Store picked up your prescription',
            "{$shopName} is now reviewing your prescription #{$prescription->id}.",
            route('customer.prescriptions.show', $prescription, absolute: false),
            'ri-store-2-line',
            'info',
        ));

        return redirect()->route('store.prescriptions.show', $prescription)
            ->with('status', 'Prescription claimed — you can now review it and contact the customer.');
    }

    /**
     * The Store organizer's actual "update the details" action — medicine
     * lines, total, call outcome and status all land directly on this row
     * (no separate Order yet, see the migration's doc comment).
     *
     * `status` comes from which of the three submit buttons on the form was
     * clicked (they share `name="status"` with different values) rather
     * than a dropdown — a dropdown left on its default was exactly how a
     * Store could save items/total without the Customer ever seeing them
     * (status stayed `reviewing` instead of moving to
     * `awaiting_confirmation`). `confirmed`/`rejected` are reachable here as
     * well as on the Customer side (see
     * Customer\PrescriptionController::accept/reject) because the
     * Customer's agreement can come back verbally on the call, not just
     * through their own panel. `dispatched` is deliberately not settable
     * here; it only happens via assignCaptain() below, once already
     * confirmed.
     */
    public function update(Request $request, Prescription $prescription)
    {
        $this->authorizeOwned($request, $prescription);

        $data = $request->validate([
            'items' => ['nullable', 'array'],
            'items.*.name' => ['required_with:items', 'string', 'max:191'],
            'items.*.quantity' => ['required_with:items', 'integer', 'min:1'],
            'items.*.price' => ['required_with:items', 'numeric', 'min:0'],
            'total_amount' => ['nullable', 'numeric', 'min:0'],
            'call_notes' => ['nullable', 'string', 'max:2000'],
            'called' => ['nullable', 'boolean'],
            'status' => ['required', 'in:reviewing,contacted,awaiting_confirmation,confirmed,rejected'],
            // Required only when rejecting on the customer's behalf — same
            // reasoning as Customer::reject() itself: a bare rejection
            // leaves no record of why, for either side to look back on.
            'rejection_remark' => ['required_if:status,rejected', 'nullable', 'string', 'max:1000'],
        ]);

        $totalAmount = $data['total_amount'] ?? $prescription->total_amount;

        // Can't send an estimate the Customer (or the Store, on their
        // behalf) can act on without an actual total on it.
        if (in_array($data['status'], ['awaiting_confirmation', 'confirmed'], true) && ! $totalAmount) {
            return back()->withInput()->withErrors([
                'total_amount' => 'Add a total amount before sending or confirming an estimate.',
            ]);
        }

        $prescription->update([
            'items' => $data['items'] ?? $prescription->items,
            'total_amount' => $totalAmount,
            'call_notes' => $data['call_notes'] ?? $prescription->call_notes,
            'called_at' => $request->boolean('called') ? now() : $prescription->called_at,
            'status' => $data['status'],
            // The Store making this call itself (not the Customer's own
            // accept/reject) — leave customer_decided_at null so the show
            // view can tell "customer clicked Accept" apart from "Store
            // marked it agreed on the phone".
            ...($data['status'] === 'rejected' ? ['rejection_remark' => $data['rejection_remark']] : []),
            // Order is now final — mint its Order ID under this Store's own
            // prefix (see Prescription::generateOrderNumber). Guarded so a
            // re-submit of the same 'confirmed' status never overwrites it.
            ...($data['status'] === 'confirmed' && ! $prescription->order_number
                ? ['order_number' => Prescription::generateOrderNumber($request->user()->store?->order_prefix)]
                : []),
        ]);

        // Spelled out with the actual amount/item count rather than a bare
        // "sent" — the Store just typed these in and wants confirmation of
        // what went out, not just that something did.
        if ($data['status'] === 'awaiting_confirmation') {
            $itemCount = count($data['items'] ?? $prescription->items ?? []);
            $statusMessage = 'Estimate of ₹'.number_format((float) $totalAmount, 2)
                .' ('.$itemCount.' item'.($itemCount === 1 ? '' : 's').') sent to the customer — they can now accept or reject it from their panel.';
        } else {
            $statusMessage = [
                'reviewing' => 'Progress saved.',
                'contacted' => 'Progress saved.',
                'confirmed' => 'Order confirmed — you can now assign a Captain.',
                'rejected' => 'Prescription rejected.',
            ][$data['status']];
        }

        // Only these three statuses are worth interrupting the Customer
        // for — reviewing/contacted are internal progress that doesn't
        // change anything actionable on their side. `confirmed`/`rejected`
        // fire here only for the Store-driven path (the phone-call
        // outcome); the Customer's own accept()/reject() notify the Store
        // instead, so neither side gets told about its own action.
        $customerNotice = [
            'awaiting_confirmation' => ['Price estimate ready', 'Review and accept/reject your ₹'.number_format((float) $totalAmount, 2)." estimate for prescription #{$prescription->id}.", 'ri-price-tag-3-line', 'primary'],
            'confirmed' => ['Order confirmed', "Your order for prescription #{$prescription->id} has been confirmed — the store is arranging delivery.", 'ri-checkbox-circle-line', 'success'],
            // The array literal below is built eagerly regardless of which
            // status actually happened, so `$data['rejection_remark']` must
            // be null-coalesced — it's absent from $data entirely unless
            // this status is 'rejected' (that's the only branch
            // required_if applies to).
            'rejected' => ['Prescription not fulfilled', "Your prescription #{$prescription->id} could not be fulfilled — reason: ".($data['rejection_remark'] ?? ''), 'ri-close-circle-line', 'danger'],
        ][$data['status']] ?? null;

        if ($customerNotice) {
            [$title, $body, $icon, $color] = $customerNotice;
            $prescription->customer->notify(new PrescriptionEventNotification(
                $prescription, $title, $body, route('customer.prescriptions.show', $prescription, absolute: false), $icon, $color,
            ));
        }

        return redirect()->route('store.prescriptions.show', $prescription)->with('status', $statusMessage);
    }

    /**
     * In-app dispatch to one of this Store's own Captains — only once the
     * Customer has agreed to the estimate (confirmed), whether that came
     * through their own panel or verbally on the call. For anyone else — a
     * family member, a third-party rider, any Captain not on the platform
     * — the show view offers the Maps/WhatsApp share links instead
     * (Prescription::googleMapsUrl()), since that hand-off happens outside
     * the software entirely and isn't gated on this.
     *
     * Payment method is captured here too — it's the natural moment the
     * Store already knows how this order is being paid for, and the
     * Captain needs to know before they leave whether to collect cash.
     * `payment_status` starts at `pending` for COD (nothing collected yet)
     * or `not_required` for prepaid (see Prescription::paymentStatusLabel
     * and the add_payment_and_delivery_fields_to_prescriptions_table
     * migration for the full pending -> collected -> settled progression).
     */
    public function assignCaptain(Request $request, Prescription $prescription)
    {
        $this->authorizeOwned($request, $prescription);

        abort_unless($prescription->status === 'confirmed', 422, 'The customer needs to accept the estimate before a Captain can be assigned.');

        $data = $request->validate([
            'captain_id' => ['required', 'exists:users,id'],
            'payment_method' => ['required', 'in:prepaid,cod'],
        ]);

        $captain = $request->user()->captains()->findOrFail($data['captain_id']);

        $prescription->update([
            'captain_id' => $captain->id,
            'status' => 'dispatched',
            'payment_method' => $data['payment_method'],
            'payment_status' => $data['payment_method'] === 'cod' ? 'pending' : 'not_required',
        ]);

        $prescription->customer->notify(new PrescriptionEventNotification(
            $prescription,
            'Out for delivery',
            "{$captain->first_name} is bringing your order for prescription #{$prescription->id}.",
            route('customer.prescriptions.show', $prescription, absolute: false),
            'ri-e-bike-2-line',
            'success',
        ));

        $paymentNote = $data['payment_method'] === 'cod'
            ? ' — collect ₹'.number_format((float) $prescription->total_amount, 2).' cash on delivery.'
            : ' — already paid (prepaid).';
        $captain->notify(new PrescriptionEventNotification(
            $prescription,
            'New delivery assigned',
            "Deliver prescription #{$prescription->id} to {$prescription->delivery_address}".$paymentNote,
            route('captain.dashboard', absolute: false),
            'ri-e-bike-2-line',
            'primary',
        ));

        return redirect()->route('store.prescriptions.show', $prescription)
            ->with('status', "Assigned to {$captain->first_name}.");
    }

    /**
     * Streams an uploaded file back from the private disk — gated to
     * unclaimed prescriptions (any Store can preview before claiming) or
     * ones this Store already claimed.
     */
    public function file(Request $request, Prescription $prescription, int $index)
    {
        $this->authorizeVisible($request, $prescription);

        $path = $prescription->files[$index] ?? abort(404);

        return Storage::disk('local')->response($path);
    }

    /**
     * Chat with the Customer — only once claimed (authorizeOwned already
     * enforces that: store_id must match exactly, not just be visible).
     * The show page submits this over AJAX so sending a message doesn't
     * reload the page (which used to reset the auto-refresh toggle) —
     * an AJAX request gets the refreshed message list straight back,
     * same as messages() below. A plain form submit (JS disabled) still
     * falls back to the old redirect.
     */
    public function sendMessage(Request $request, Prescription $prescription)
    {
        $this->authorizeOwned($request, $prescription);

        $data = $request->validate(['body' => ['required', 'string', 'max:2000']]);

        $prescription->messages()->create([
            'user_id' => $request->user()->id,
            'body' => $data['body'],
        ]);

        $shopName = $request->user()->store?->shop_name ?? 'The store';
        $prescription->customer->notify(new PrescriptionEventNotification(
            $prescription,
            "New message from {$shopName}",
            Str::limit($data['body'], 100),
            route('customer.prescriptions.show', $prescription, absolute: false).'#chat',
            'ri-message-3-line',
            'info',
        ));

        if ($request->ajax()) {
            return view('Store.prescriptions._messages', [
                'prescription' => $prescription,
                'messages' => $prescription->messages()->with('sender')->get(),
            ]);
        }

        return redirect(route('store.prescriptions.show', $prescription).'#chat');
    }

    /**
     * Returns just the rendered message list — the show page's Refresh
     * button (and optional auto-update toggle) fetch this and swap it in.
     */
    public function messages(Request $request, Prescription $prescription)
    {
        $this->authorizeOwned($request, $prescription);

        return view('Store.prescriptions._messages', [
            'prescription' => $prescription,
            'messages' => $prescription->messages()->with('sender')->get(),
        ]);
    }

    protected function authorizeVisible(Request $request, Prescription $prescription): void
    {
        abort_unless($prescription->store_id === null || $prescription->store_id === $request->user()->id, 404);
    }

    protected function authorizeOwned(Request $request, Prescription $prescription): void
    {
        abort_unless($prescription->store_id === $request->user()->id, 403);
    }
}
