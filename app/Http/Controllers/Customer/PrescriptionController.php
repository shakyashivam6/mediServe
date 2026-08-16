<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Prescription;
use App\Models\User;
use App\Notifications\PrescriptionEventNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PrescriptionController extends Controller
{
    /**
     * A Customer's own upload history — doubles as their "home" screen
     * (see routes/web.php: OTP verify lands here). Empty state points them
     * at the upload form, which is meant to be the very first thing a new
     * Customer does.
     */
    public function index(Request $request)
    {
        return view('Customer.prescriptions.index', [
            'prescriptions' => $request->user()->prescriptions()->latest()->get(),
        ]);
    }

    /**
     * Pre-fills the delivery address/location from the Customer's most
     * recent prescription, if they have one — saves re-typing an address
     * that rarely changes between orders. Still a plain editable field/pin,
     * not a saved-addresses list (no such table yet, see roadmap Phase 5).
     */
    public function create(Request $request)
    {
        $lastPrescription = $request->user()->prescriptions()
            ->latest()
            ->first(['delivery_address', 'latitude', 'longitude']);

        return view('Customer.prescriptions.create', [
            'savedAddress' => $lastPrescription?->delivery_address,
            'savedLatitude' => $lastPrescription?->latitude,
            'savedLongitude' => $lastPrescription?->longitude,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'files' => ['required', 'array', 'min:1', 'max:5'],
            'files.*' => ['file', 'mimes:jpg,jpeg,png,pdf', 'max:10240'],
            'remark' => ['nullable', 'string', 'max:1000'],
            'delivery_address' => ['required', 'string', 'max:1000'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
        ]);

        $paths = collect($request->file('files'))
            ->map(fn ($file) => $file->store('prescriptions/'.$request->user()->id, 'local'))
            ->all();

        $prescription = $request->user()->prescriptions()->create([
            'files' => $paths,
            'remark' => $data['remark'] ?? null,
            'delivery_address' => $data['delivery_address'],
            'latitude' => $data['latitude'] ?? null,
            'longitude' => $data['longitude'] ?? null,
            'status' => 'pending',
        ]);

        // Shared-queue routing (see project memory) means every approved,
        // active Store can claim this — so every one of them hears about
        // it, not just one. First to open store.prescriptions.show and
        // claim() owns it from there.
        $approvedStores = User::where('role', 'store')
            ->where('isActive', true)
            ->whereHas('store', fn ($q) => $q->where('status', 'approved'))
            ->get();

        foreach ($approvedStores as $store) {
            $store->notify(new PrescriptionEventNotification(
                $prescription,
                'New prescription request',
                "{$request->user()->first_name} uploaded a new prescription — open it to review and claim.",
                route('store.prescriptions.show', $prescription),
                'ri-file-add-line',
                'info',
            ));
        }

        return redirect()->route('customer.prescriptions.show', $prescription)
            ->with('status', 'Prescription uploaded — a store will review it and call you shortly.');
    }

    public function show(Request $request, Prescription $prescription)
    {
        $this->authorizeOwner($request, $prescription);

        $prescription->load('store', 'captain');

        return view('Customer.prescriptions.show', ['prescription' => $prescription]);
    }

    /**
     * Customer accepts the Store's priced estimate from their own panel —
     * the alternative to agreeing verbally on the call, in which case the
     * Store records it instead (see Store\PrescriptionController::update).
     * Only a Captain assignment (assignCaptain) is gated on this; nothing
     * else in the flow requires the Customer to use the panel at all.
     */
    public function accept(Request $request, Prescription $prescription)
    {
        $this->authorizeOwner($request, $prescription);

        abort_unless($prescription->status === 'awaiting_confirmation', 422, 'This prescription has no pending estimate to accept.');

        $prescription->update([
            'status' => 'confirmed',
            'customer_decided_at' => now(),
        ]);

        $prescription->store?->notify(new PrescriptionEventNotification(
            $prescription,
            'Customer accepted the estimate',
            "{$request->user()->first_name} accepted the ₹".number_format((float) $prescription->total_amount, 2)." estimate for prescription #{$prescription->id}. You can now assign a Captain.",
            route('store.prescriptions.show', $prescription),
            'ri-thumb-up-line',
            'success',
        ));

        return redirect()->route('customer.prescriptions.show', $prescription)
            ->with('status', 'Estimate accepted — the store will arrange delivery.');
    }

    public function reject(Request $request, Prescription $prescription)
    {
        $this->authorizeOwner($request, $prescription);

        abort_unless($prescription->status === 'awaiting_confirmation', 422, 'This prescription has no pending estimate to reject.');

        $data = $request->validate([
            'customer_decision_note' => ['nullable', 'string', 'max:1000'],
        ]);

        $prescription->update([
            'status' => 'rejected',
            'customer_decided_at' => now(),
            'customer_decision_note' => $data['customer_decision_note'] ?? null,
        ]);

        $prescription->store?->notify(new PrescriptionEventNotification(
            $prescription,
            'Customer rejected the estimate',
            "{$request->user()->first_name} rejected the estimate for prescription #{$prescription->id}.",
            route('store.prescriptions.show', $prescription),
            'ri-thumb-down-line',
            'danger',
        ));

        return redirect()->route('customer.prescriptions.show', $prescription)
            ->with('status', 'Estimate rejected.');
    }

    /**
     * Streams an uploaded file back from the private disk — gated to the
     * owning Customer, same shape as Store\PrescriptionController::file().
     */
    public function file(Request $request, Prescription $prescription, int $index)
    {
        $this->authorizeOwner($request, $prescription);

        $path = $prescription->files[$index] ?? abort(404);

        return Storage::disk('local')->response($path);
    }

    /**
     * Chat with the claiming Store — only meaningful once one exists, see
     * the `store_id` check in the show view. The show page submits this
     * over AJAX so sending a message doesn't reload the page (which used
     * to reset the auto-refresh toggle) — an AJAX request gets the
     * refreshed message list straight back, same as messages() below. A
     * plain form submit (JS disabled) still falls back to the old redirect.
     */
    public function sendMessage(Request $request, Prescription $prescription)
    {
        $this->authorizeOwner($request, $prescription);

        abort_unless($prescription->store_id !== null, 422, 'No store has picked this up yet.');

        $data = $request->validate(['body' => ['required', 'string', 'max:2000']]);

        $prescription->messages()->create([
            'user_id' => $request->user()->id,
            'body' => $data['body'],
        ]);

        $prescription->store?->notify(new PrescriptionEventNotification(
            $prescription,
            'New message from '.$request->user()->first_name,
            Str::limit($data['body'], 100),
            route('store.prescriptions.show', $prescription).'#chat',
            'ri-message-3-line',
            'info',
        ));

        if ($request->ajax()) {
            return view('Customer.prescriptions._messages', [
                'prescription' => $prescription,
                'messages' => $prescription->messages()->with('sender')->get(),
            ]);
        }

        return redirect(route('customer.prescriptions.show', $prescription).'#chat');
    }

    /**
     * Returns just the rendered message list — the show page's Refresh
     * button (and optional auto-update toggle) fetch this and swap it in,
     * rather than reloading the whole page.
     */
    public function messages(Request $request, Prescription $prescription)
    {
        $this->authorizeOwner($request, $prescription);

        return view('Customer.prescriptions._messages', [
            'prescription' => $prescription,
            'messages' => $prescription->messages()->with('sender')->get(),
        ]);
    }

    protected function authorizeOwner(Request $request, Prescription $prescription): void
    {
        abort_unless($prescription->user_id === $request->user()->id, 404);
    }
}
