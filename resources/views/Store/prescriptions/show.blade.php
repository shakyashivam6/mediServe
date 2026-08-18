@php
    $variant = $statusVariant[$prescription->status];
    $isClaimedByMe = $prescription->store_id === auth()->id();
    $isUnclaimed = $prescription->store_id === null;
    $mapsUrl = $prescription->googleMapsUrl();
    $whatsappText = $mapsUrl
        ? rawurlencode("Delivery for {$prescription->customer->first_name} — {$prescription->delivery_address}\n{$mapsUrl}")
        : null;
@endphp
<x-layouts.store-layout title="Prescription #{{ $prescription->id }}">

    @if (session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger">{{ $errors->first() }}</div>
    @endif

    <div class="row">
        <div class="col-12">
            <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
                <div class="d-flex align-items-center gap-2">
                    <h4 class="mb-0">Prescription #{{ $prescription->id }}</h4>
                    <span class="badge bg-{{ $variant }}">{{ $isUnclaimed ? 'New — unclaimed' : ucfirst(str_replace('_', ' ', $prescription->status)) }}</span>
                    @if ($prescription->payment_method)
                        <span class="badge bg-{{ $prescription->isCodAmountPending() ? 'danger' : ($prescription->isCodAwaitingSettlement() ? 'warning' : 'secondary') }}">
                            {{ $prescription->paymentStatusLabel() }}
                        </span>
                    @endif
                </div>
                <a href="{{ route('store.prescriptions.index') }}" class="btn btn-soft-secondary btn-sm">
                    <i class="ri-arrow-left-line align-middle"></i> Back to list
                </a>
            </div>
        </div>
    </div>

    @if ($isUnclaimed)
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <p class="mb-0">This prescription hasn't been claimed yet. Claim it to start reviewing and contacting the customer.</p>
                        <form method="POST" action="{{ route('store.prescriptions.claim', $prescription) }}">
                            @csrf
                            <button type="submit" class="btn btn-primary"><i class="ri-hand-coin-line align-middle me-1"></i> Claim Prescription</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @elseif (! $isClaimedByMe)
        <div class="alert alert-warning">This prescription was already claimed by another store.</div>
    @endif

    @if ($prescription->status === 'rejected' && $prescription->rejection_remark)
        <div class="row">
            <div class="col-12">
                <div class="alert alert-danger d-flex align-items-start" role="alert">
                    <iconify-icon icon="solar:close-circle-bold-duotone" class="fs-24 me-2 flex-shrink-0"></iconify-icon>
                    <div>
                        <strong>Rejected — reason given:</strong>
                        <p class="mb-0">{{ $prescription->rejection_remark }}</p>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="row row-cols-1 row-cols-lg-2">
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <h5 class="header-title mb-3">Customer &amp; Delivery</h5>
                    <p class="mb-1"><strong>{{ $prescription->customer->first_name }} {{ $prescription->customer->second_name }}</strong></p>
                    <p class="mb-2 text-muted">{{ $prescription->customer->mobile }}</p>
                    @if ($prescription->remark)
                        <p class="mb-2"><strong>Remark:</strong> {{ $prescription->remark }}</p>
                    @endif
                    <p class="mb-2"><strong>Deliver to:</strong> {{ $prescription->delivery_address }}</p>

                    @if ($prescription->latitude && $prescription->longitude)
                        <p class="mb-2">
                            <strong>Location:</strong> {{ $prescription->latitude }}, {{ $prescription->longitude }}
                            @if ($distanceKm !== null)
                                <span class="text-muted">(~{{ number_format($distanceKm, 1) }} km from your store)</span>
                            @endif
                        </p>
                        <div class="d-flex flex-wrap gap-2 mb-2">
                            <a href="{{ $mapsUrl }}" target="_blank" class="btn btn-soft-primary btn-sm">
                                <i class="ri-map-pin-line align-middle"></i> Open in Google Maps
                            </a>
                            <a href="https://wa.me/?text={{ $whatsappText }}" target="_blank" class="btn btn-soft-success btn-sm">
                                <i class="ri-whatsapp-line align-middle"></i> Share via WhatsApp
                            </a>
                        </div>
                        <p class="text-muted font-13 mb-0">Use these to hand the location to any captain — in-app or not.</p>
                    @else
                        <p class="text-muted font-13 mb-0">Customer didn't share a GPS location — use the delivery address above.</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="col">
            <div class="card">
                <div class="card-body">
                    <h5 class="header-title mb-3">Uploaded Files</h5>
                    <div class="d-flex flex-wrap gap-2">
                        @foreach ($prescription->files as $index => $file)
                            <a href="{{ route('store.prescriptions.file', [$prescription, $index]) }}" target="_blank" class="btn btn-soft-secondary btn-sm">
                                <i class="ri-file-line align-middle"></i> File {{ $index + 1 }}
                            </a>
                        @endforeach
                    </div>

                    @if ($prescription->called_at || $prescription->call_notes)
                        <hr class="my-3">
                        <h5 class="header-title mb-2">Call Log</h5>
                        @if ($prescription->called_at)
                            <p class="mb-1 text-muted font-13">Called {{ $prescription->called_at->format('d M Y, h:i A') }}</p>
                        @endif
                        @if ($prescription->call_notes)
                            <p class="mb-0">{{ $prescription->call_notes }}</p>
                        @endif
                    @endif

                </div>
            </div>
        </div>
    </div>

    @if ($isClaimedByMe && ! in_array($prescription->status, ['dispatched', 'delivered', 'rejected'], true))
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="header-title mb-3">Update Details</h5>
                        <p class="text-muted font-13">
                            Add the medicines &amp; price you quoted on the call, then pick what happens next — each button below saves
                            everything on this form first.
                        </p>

                        <form method="POST" action="{{ route('store.prescriptions.update', $prescription) }}">
                            @csrf
                            @method('PUT')

                            <div class="table-responsive mb-2">
                                <table class="table table-sm align-middle" id="items-table">
                                    <thead>
                                        <tr>
                                            <th>Medicine</th>
                                            <th style="width:110px;">Qty</th>
                                            <th style="width:150px;">Price (₹)</th>
                                            <th style="width:40px;"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach (($prescription->items ?: [['name' => '', 'quantity' => 1, 'price' => '']]) as $i => $item)
                                            <tr>
                                                <td><input type="text" name="items[{{ $i }}][name]" class="form-control form-control-sm" value="{{ $item['name'] ?? '' }}" placeholder="e.g. Paracetamol 500mg"></td>
                                                <td><input type="number" min="1" name="items[{{ $i }}][quantity]" class="form-control form-control-sm item-qty" value="{{ $item['quantity'] ?? 1 }}"></td>
                                                <td><input type="number" step="0.01" min="0" name="items[{{ $i }}][price]" class="form-control form-control-sm item-price" value="{{ $item['price'] ?? '' }}"></td>
                                                <td><button type="button" class="btn btn-soft-danger btn-sm remove-row"><i class="ri-close-line"></i></button></td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <button type="button" id="add-item-row" class="btn btn-soft-primary btn-sm mb-3"><i class="ri-add-line align-middle"></i> Add medicine</button>

                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="total_amount" class="form-label">Total Amount (₹)</label>
                                    <input type="number" step="0.01" min="0" name="total_amount" id="total_amount" class="form-control @error('total_amount') is-invalid @enderror" value="{{ old('total_amount', $prescription->total_amount) }}">
                                    <div class="form-text">Auto-summed from lines above — edit freely to add delivery charges etc.</div>
                                    @error('total_amount')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-8 mb-3">
                                    <label for="call_notes" class="form-label">Call notes</label>
                                    <textarea name="call_notes" id="call_notes" class="form-control" rows="2">{{ old('call_notes', $prescription->call_notes) }}</textarea>
                                </div>
                            </div>

                            <div class="form-check mb-3">
                                <input type="checkbox" name="called" value="1" class="form-check-input" id="called" {{ $prescription->called_at ? 'checked' : '' }}>
                                <label class="form-check-label" for="called">Mark as called {{ $prescription->called_at ? '(already logged, will refresh timestamp)' : 'just now' }}</label>
                            </div>

                            <div class="mb-3">
                                <label for="rejection_remark" class="form-label">Rejection reason <span class="text-muted font-13">(only needed if you click Reject below)</span></label>
                                <textarea name="rejection_remark" id="rejection_remark" class="form-control @error('rejection_remark') is-invalid @enderror" rows="2" placeholder="e.g. Out of stock, can't deliver to this address…">{{ old('rejection_remark') }}</textarea>
                                @error('rejection_remark')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <hr class="my-3">

                            <div class="d-flex flex-wrap gap-2">
                                {{-- Every status reachable inside this block (reviewing/contacted/
                                     awaiting_confirmation/confirmed) is a valid value on its own —
                                     re-submitting the current one just saves the form fields without
                                     moving the status forward or backward. --}}
                                <button type="submit" name="status" value="{{ $prescription->status }}" class="btn btn-soft-secondary">
                                    <i class="ri-save-line align-middle"></i> Save Progress
                                </button>
                                <button type="submit" name="status" value="awaiting_confirmation" class="btn btn-primary">
                                    <i class="ri-send-plane-line align-middle"></i> Send Estimate to Customer
                                </button>
                                <button type="submit" name="status" value="confirmed" class="btn btn-success">
                                    <i class="ri-check-double-line align-middle"></i> Confirm Order (customer agreed on call)
                                </button>
                                <button type="submit" name="status" value="rejected" id="reject-btn" class="btn btn-outline-danger ms-auto">
                                    Reject
                                </button>
                            </div>
                            <p class="text-muted font-13 mt-2 mb-0">
                                <strong>Send Estimate</strong> puts it on the customer's own panel to Accept/Reject. <strong>Confirm Order</strong> skips that —
                                use it once the customer has already said yes on the call. Either way, a Captain can only be assigned after this reaches Confirmed.
                            </p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @elseif ($isClaimedByMe && $prescription->items)
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="header-title mb-3">{{ $prescription->status === 'rejected' ? 'Estimate (rejected)' : 'Estimate' }}</h5>
                        <table class="table table-sm">
                            <tbody>
                                @foreach ($prescription->items as $item)
                                    <tr>
                                        <td>{{ $item['name'] ?? '—' }} &times; {{ $item['quantity'] ?? '—' }}</td>
                                        <td class="text-end">₹{{ number_format((float) ($item['price'] ?? 0), 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @if ($prescription->total_amount !== null)
                            <p class="fw-bold mb-0">Total: ₹{{ number_format((float) $prescription->total_amount, 2) }}</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($isClaimedByMe)
        @if (in_array($prescription->status, ['confirmed', 'dispatched', 'delivered'], true))
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="header-title mb-3">{{ $prescription->captain ? 'Delivery' : 'Assign Captain' }}</h5>

                            @if ($prescription->captain)
                                <p class="mb-1">Assigned to <strong>{{ $prescription->captain->first_name }} {{ $prescription->captain->second_name }}</strong> ({{ $prescription->captain->mobile }}).</p>
                                <p class="mb-2 text-muted">
                                    {{ $prescription->payment_method === 'cod' ? 'Cash on Delivery' : 'Prepaid' }} — {{ $prescription->paymentStatusLabel() }}
                                    @if ($prescription->status === 'delivered')
                                        &middot; Delivered {{ $prescription->delivered_at?->format('d M Y, h:i A') }}
                                    @endif
                                </p>
                            @endif

                            @if ($prescription->status === 'confirmed')
                                @if ($captains->isEmpty())
                                    <p class="text-muted mb-0">You have no active Captains yet — <a href="{{ route('store.captains.create') }}">add one</a>, or use the WhatsApp/Maps share above to hand this off externally.</p>
                                @else
                                    <form method="POST" action="{{ route('store.prescriptions.assign-captain', $prescription) }}">
                                        @csrf
                                        <div class="d-flex gap-2 flex-wrap align-items-start">
                                            <select name="captain_id" class="form-select @error('captain_id') is-invalid @enderror" style="max-width:260px;" required>
                                                <option value="">Select a captain…</option>
                                                @foreach ($captains as $captain)
                                                    <option value="{{ $captain->id }}" {{ old('captain_id') == $captain->id ? 'selected' : '' }}>{{ $captain->first_name }} {{ $captain->second_name }}</option>
                                                @endforeach
                                            </select>

                                            <div class="btn-group" role="group">
                                                <input type="radio" class="btn-check" name="payment_method" id="payment-prepaid" value="prepaid" {{ old('payment_method') !== 'cod' ? 'checked' : '' }} required>
                                                <label class="btn btn-outline-primary btn-sm" for="payment-prepaid">Prepaid</label>

                                                <input type="radio" class="btn-check" name="payment_method" id="payment-cod" value="cod" {{ old('payment_method') === 'cod' ? 'checked' : '' }} required>
                                                <label class="btn btn-outline-warning btn-sm" for="payment-cod">Cash on Delivery</label>
                                            </div>

                                            <button type="submit" class="btn btn-primary">Assign &amp; Dispatch</button>
                                        </div>
                                        <p class="text-muted font-13 mt-2 mb-0">Cash on Delivery tells the Captain to collect ₹{{ number_format((float) $prescription->total_amount, 2) }} from the customer at the door.</p>
                                    </form>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endif

    @if ($isClaimedByMe)
        <div class="row">
            <div class="col-12">
                <div class="card" id="chat">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
                            <h5 class="header-title mb-0">Chat with Customer</h5>
                            <div class="d-flex align-items-center gap-2">
                                <div class="form-check form-switch mb-0">
                                    <input type="checkbox" class="form-check-input" id="chat-auto-toggle">
                                    <label class="form-check-label font-13" for="chat-auto-toggle">Auto-refresh (10s)</label>
                                </div>
                                <button type="button" id="chat-refresh" class="btn btn-soft-secondary btn-sm">
                                    <i class="ri-refresh-line align-middle"></i> Refresh
                                </button>
                            </div>
                        </div>

                        <div id="chat-messages" style="max-height:340px; overflow-y:auto;" class="border rounded p-2 bg-light-subtle">
                            @include('Store.prescriptions._messages', ['messages' => $prescription->messages()->with('sender')->get()])
                        </div>

                        <form id="chat-form" method="POST" action="{{ route('store.prescriptions.messages.store', $prescription) }}" class="d-flex gap-2 mt-3">
                            @csrf
                            <input type="text" name="body" id="chat-body" class="form-control" placeholder="Type a message…" required maxlength="2000">
                            <button type="submit" class="btn btn-primary">Send</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        @push('scripts')
            <script>
                $(function () {
                    const messagesUrl = @json(route('store.prescriptions.messages.index', $prescription));
                    const $messages = $('#chat-messages');
                    const $form = $('#chat-form');
                    const $body = $('#chat-body');
                    let autoTimer = null;

                    function refreshMessages() {
                        $.get(messagesUrl, function (html) {
                            const el = $messages.get(0);
                            const wasAtBottom = el.scrollTop + el.clientHeight >= el.scrollHeight - 10;
                            $messages.html(html);
                            if (wasAtBottom) el.scrollTop = el.scrollHeight;
                        });
                    }

                    $('#chat-refresh').on('click', refreshMessages);

                    $('#chat-auto-toggle').on('change', function () {
                        if (this.checked) {
                            autoTimer = setInterval(refreshMessages, 10000);
                        } else if (autoTimer) {
                            clearInterval(autoTimer);
                            autoTimer = null;
                        }
                    });

                    // Submitted over AJAX rather than a plain POST — a full
                    // page reload here used to clear the auto-refresh toggle
                    // above, forcing it back on after every message sent.
                    $form.on('submit', function (e) {
                        e.preventDefault();

                        const body = $body.val().trim();
                        if (!body) return;

                        const $btn = $form.find('button[type=submit]');
                        $btn.prop('disabled', true);

                        $.post($form.attr('action'), $form.serialize())
                            .done(function (html) {
                                $messages.html(html);
                                $body.val('').trigger('focus');
                                const el = $messages.get(0);
                                el.scrollTop = el.scrollHeight;
                            })
                            .fail(function () {
                                alert('Could not send the message — please try again.');
                            })
                            .always(function () {
                                $btn.prop('disabled', false);
                            });
                    });

                    // Land already scrolled to the latest message.
                    const el = $messages.get(0);
                    el.scrollTop = el.scrollHeight;
                });
            </script>
        @endpush
    @endif

    @push('scripts')
        <script>
            (function () {
                const tbody = document.querySelector('#items-table tbody');
                const totalInput = document.getElementById('total_amount');

                function rowCount() {
                    return tbody.querySelectorAll('tr').length;
                }

                function recalcTotal() {
                    let sum = 0;
                    tbody.querySelectorAll('tr').forEach(function (row) {
                        const qty = parseFloat(row.querySelector('.item-qty')?.value || 0);
                        const price = parseFloat(row.querySelector('.item-price')?.value || 0);
                        if (!isNaN(qty) && !isNaN(price)) sum += qty * price;
                    });
                    totalInput.value = sum.toFixed(2);
                }

                document.getElementById('add-item-row')?.addEventListener('click', function () {
                    const i = rowCount();
                    const tr = document.createElement('tr');
                    tr.innerHTML = '<td><input type="text" name="items[' + i + '][name]" class="form-control form-control-sm" placeholder="e.g. Paracetamol 500mg"></td>'
                        + '<td><input type="number" min="1" name="items[' + i + '][quantity]" class="form-control form-control-sm item-qty" value="1"></td>'
                        + '<td><input type="number" step="0.01" min="0" name="items[' + i + '][price]" class="form-control form-control-sm item-price"></td>'
                        + '<td><button type="button" class="btn btn-soft-danger btn-sm remove-row"><i class="ri-close-line"></i></button></td>';
                    tbody.appendChild(tr);
                });

                tbody.addEventListener('click', function (e) {
                    if (e.target.closest('.remove-row')) {
                        e.target.closest('tr').remove();
                        recalcTotal();
                    }
                });

                tbody.addEventListener('input', function (e) {
                    if (e.target.classList.contains('item-qty') || e.target.classList.contains('item-price')) {
                        recalcTotal();
                    }
                });

                // The remark field isn't marked `required` at the HTML level
                // — that would also block Save Progress/Send Estimate/Confirm,
                // which share this same form and don't need a reason. Instead
                // only the Reject button's own click is gated on it (the
                // server enforces the same rule either way via
                // required_if:status,rejected, so this is just the earlier,
                // friendlier check).
                document.getElementById('reject-btn')?.addEventListener('click', function (e) {
                    const remark = document.getElementById('rejection_remark');
                    if (!remark.value.trim()) {
                        e.preventDefault();
                        remark.focus();
                        alert('Please add a reason before rejecting.');
                        return;
                    }
                    if (!confirm('Reject this prescription? This cannot be undone from here.')) {
                        e.preventDefault();
                    }
                });
            })();
        </script>
    @endpush

</x-layouts.store-layout>
