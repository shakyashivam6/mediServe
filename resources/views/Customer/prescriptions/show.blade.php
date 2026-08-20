@php
    $statusCopy = [
        'pending' => 'Waiting for a store to pick this up.',
        'reviewing' => 'A store is reviewing your prescription.',
        'contacted' => 'The store has called you about this.',
        'awaiting_confirmation' => 'The store has sent a price estimate — review it below.',
        'confirmed' => 'Estimate accepted — the store is arranging delivery.',
        'dispatched' => 'Out for delivery — see your captain\'s details below.',
        'delivered' => 'Delivered.',
        'rejected' => 'This prescription was not fulfilled.',
    ][$prescription->status] ?? '';
@endphp
<x-layouts.customer-layout title="Prescription #{{ $prescription->id }}">

    <a href="{{ route('customer.prescriptions.index') }}" style="font-size:13px; color:var(--ink-soft); text-decoration:underline;">&larr; All prescriptions</a>

    <div class="card" style="margin-top:12px;">
        <div style="display:flex; align-items:center; justify-content:space-between; gap:10px;">
            <h2 style="margin:0;">Prescription #{{ $prescription->id }}</h2>
            <span class="status-badge status-{{ $prescription->status }}">{{ $prescription->customerStatusLabel() }}</span>
        </div>
        <p style="color:var(--ink-soft); font-size:13.5px; margin:8px 0 0;">{{ $statusCopy }}</p>
        <p class="hint" style="margin-top:4px;">Uploaded {{ $prescription->created_at->format('d M Y, h:i A') }}</p>
    </div>

    @if ($prescription->status === 'rejected' && $prescription->rejection_remark)
        <div class="card" style="border-color:var(--red); background:var(--red-soft);">
            <h3 style="margin-top:0; color:#991b1b;">Reason for rejection</h3>
            <p style="margin:0; font-size:14.5px; color:#991b1b;">{{ $prescription->rejection_remark }}</p>
        </div>
    @endif

    @if ($prescription->status === 'awaiting_confirmation')
        <div class="card" style="border-color:var(--blue); background:var(--blue-soft);">
            <h3 style="margin-top:0;">Store's estimate</h3>

            @if ($prescription->items)
                <table style="width:100%; border-collapse:collapse; font-size:13.5px; margin-bottom:10px;">
                    <thead>
                        <tr style="text-align:left; color:var(--ink-soft); font-size:11.5px; text-transform:uppercase;">
                            <th style="padding:4px 0;">Item</th>
                            <th style="padding:4px 0;">Qty</th>
                            <th style="padding:4px 0; text-align:right;">Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($prescription->items as $item)
                            <tr>
                                <td style="padding:4px 0;">{{ $item['name'] ?? '—' }}</td>
                                <td style="padding:4px 0;">{{ $item['quantity'] ?? '—' }}</td>
                                <td style="padding:4px 0; text-align:right;">₹{{ number_format((float) ($item['price'] ?? 0), 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif

            @if ($prescription->total_amount !== null)
                <p style="font-weight:700; font-size:16px; margin:0 0 14px;">Total: ₹{{ number_format((float) $prescription->total_amount, 2) }}</p>
            @endif

            <form method="POST" action="{{ route('customer.prescriptions.accept', $prescription) }}">
                @csrf
                <button type="submit" class="btn" style="background:var(--green);">Accept Estimate</button>
            </form>

            <form method="POST" action="{{ route('customer.prescriptions.reject', $prescription) }}" style="margin-top:12px;">
                @csrf
                <label for="rejection_remark" style="margin-top:0;">Rejecting instead? Tell the store why</label>
                <textarea id="rejection_remark" name="rejection_remark" required maxlength="1000" placeholder="e.g. Price too high, found it elsewhere, no longer needed…">{{ old('rejection_remark') }}</textarea>
                @error('rejection_remark')<p class="field-error">{{ $message }}</p>@enderror
                <button type="submit" class="btn" style="background:var(--red);" onclick="return confirm('Reject this estimate?');">Reject Estimate</button>
            </form>
        </div>
    @endif

    @if ($prescription->isCod())
        <div class="card" style="border-color:{{ $prescription->isCodAmountPending() ? 'var(--red)' : 'var(--green)' }}; background:{{ $prescription->isCodAmountPending() ? 'var(--red-soft)' : 'var(--green-soft)' }};">
            @if ($prescription->isCodAmountPending())
                <h3 style="margin-top:0;">💰 Pay on delivery</h3>
                <p style="margin:0; font-size:15px;">
                    Please keep <strong>₹{{ number_format((float) $prescription->total_amount, 2) }}</strong> ready in cash for your captain.
                </p>
            @else
                <h3 style="margin-top:0;">✅ Payment received</h3>
                <p style="margin:0; font-size:15px;">₹{{ number_format((float) $prescription->total_amount, 2) }} collected in cash — thank you.</p>
            @endif
        </div>
    @endif

    @if ($prescription->status === 'dispatched' && $prescription->captain)
        <div class="card" style="border-color:var(--green); background:var(--green-soft);">
            <h3 style="margin-top:0;">Out for delivery</h3>
            <p style="margin:0 0 4px;"><strong>{{ $prescription->captain->first_name }} {{ $prescription->captain->second_name }}</strong> is bringing your order.</p>
            <p style="margin:0 0 10px;">
                <a href="tel:{{ $prescription->captain->mobile }}" style="color:var(--green); font-weight:700; text-decoration:none;">
                    <i class="ri-phone-line"></i> {{ $prescription->captain->mobile }}
                </a>
            </p>
            <a href="{{ route('customer.prescriptions.bill', ['prescription' => $prescription, 'download' => 1]) }}" class="btn btn-soft" style="width:auto; margin:0;">
                <i class="ri-download-2-line"></i> Download Bill
            </a>
        </div>
    @endif

    @if ($prescription->status === 'delivered' && $prescription->captain)
        <div class="card">
            <h3 style="margin-top:0;">Delivered</h3>
            <p style="margin:0 0 10px; color:var(--ink-soft);">
                Delivered by <strong>{{ $prescription->captain->first_name }} {{ $prescription->captain->second_name }}</strong>
                on {{ $prescription->delivered_at?->format('d M Y, h:i A') }}.
            </p>
            <a href="{{ route('customer.prescriptions.bill', ['prescription' => $prescription, 'download' => 1]) }}" class="btn btn-soft" style="width:auto; margin:0;">
                <i class="ri-download-2-line"></i> Download Bill
            </a>
        </div>
    @endif

    @if (in_array($prescription->status, ['confirmed', 'dispatched', 'delivered']) && $prescription->items)
        <div class="card">
            <h3 style="margin-top:0;">Confirmed order</h3>
            <table style="width:100%; border-collapse:collapse; font-size:13.5px;">
                <tbody>
                    @foreach ($prescription->items as $item)
                        <tr>
                            <td style="padding:4px 0;">{{ $item['name'] ?? '—' }} &times; {{ $item['quantity'] ?? '—' }}</td>
                            <td style="padding:4px 0; text-align:right;">₹{{ number_format((float) ($item['price'] ?? 0), 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            @if ($prescription->total_amount !== null)
                <p style="font-weight:700; margin:10px 0 0;">Total: ₹{{ number_format((float) $prescription->total_amount, 2) }}</p>
            @endif
        </div>
    @endif

    <div class="card">
        <h3 style="margin-top:0;">Your upload</h3>
        @if ($prescription->remark)
            <p style="font-size:13.5px;"><strong>Remark:</strong> {{ $prescription->remark }}</p>
        @endif
        <p style="font-size:13.5px;"><strong>Delivery address:</strong> {{ $prescription->delivery_address }}</p>

        <div style="display:flex; flex-wrap:wrap; gap:8px; margin-top:10px;">
            @foreach ($prescription->files as $index => $file)
                <a href="{{ route('customer.prescriptions.file', [$prescription, $index]) }}" target="_blank" class="btn btn-soft" style="width:auto; margin:0;">
                    <i class="ri-file-line"></i> File {{ $index + 1 }}
                </a>
            @endforeach
        </div>
    </div>

    @if ($prescription->store_id)
        <div class="card" id="chat">
            <div style="display:flex; align-items:center; justify-content:space-between; gap:10px; margin-bottom:10px;">
                <h3 style="margin:0;">Chat with the store</h3>
                <div style="display:flex; align-items:center; gap:10px;">
                    <label style="display:flex; align-items:center; gap:5px; font-size:12.5px; color:var(--ink-soft); font-weight:600; margin:0;">
                        <input type="checkbox" id="chat-auto-toggle"> Auto-refresh (10s)
                    </label>
                    <button type="button" id="chat-refresh" class="btn btn-soft" style="width:auto; margin:0; padding:6px 12px; font-size:12.5px;">
                        <i class="ri-refresh-line"></i> Refresh
                    </button>
                </div>
            </div>

            <div id="chat-messages" style="display:flex; flex-direction:column; gap:10px; max-height:340px; overflow-y:auto; padding:6px; background:var(--bg); border-radius:10px;">
                @include('Customer.prescriptions._messages', ['messages' => $prescription->messages()->with('sender')->get()])
            </div>

            <form id="chat-form" method="POST" action="{{ route('customer.prescriptions.messages.store', $prescription) }}" style="display:flex; gap:8px; margin-top:10px;">
                @csrf
                <input type="text" name="body" id="chat-body" placeholder="Type a message…" required maxlength="2000" style="flex:1; margin:0;">
                <button type="submit" class="btn" style="width:auto; margin:0;">Send</button>
            </form>
        </div>

        <script>
            (function () {
                const messagesUrl = @json(route('customer.prescriptions.messages.index', $prescription));
                const messagesEl = document.getElementById('chat-messages');
                const refreshBtn = document.getElementById('chat-refresh');
                const autoToggle = document.getElementById('chat-auto-toggle');
                const form = document.getElementById('chat-form');
                const bodyInput = document.getElementById('chat-body');
                const sendBtn = form.querySelector('button[type=submit]');
                let autoTimer = null;

                function refreshMessages() {
                    fetch(messagesUrl)
                        .then(function (r) { return r.text(); })
                        .then(function (html) {
                            const wasAtBottom = messagesEl.scrollTop + messagesEl.clientHeight >= messagesEl.scrollHeight - 10;
                            messagesEl.innerHTML = html;
                            if (wasAtBottom) messagesEl.scrollTop = messagesEl.scrollHeight;
                        })
                        .catch(function () { /* silent — next manual/auto refresh will retry */ });
                }

                refreshBtn.addEventListener('click', refreshMessages);

                autoToggle.addEventListener('change', function () {
                    if (autoToggle.checked) {
                        autoTimer = setInterval(refreshMessages, 10000);
                    } else if (autoTimer) {
                        clearInterval(autoTimer);
                        autoTimer = null;
                    }
                });

                // Submitted over AJAX rather than a plain POST — a full page
                // reload here used to clear the auto-refresh toggle above,
                // forcing it back on after every message sent.
                form.addEventListener('submit', function (e) {
                    e.preventDefault();

                    const body = bodyInput.value.trim();
                    if (!body) return;

                    sendBtn.disabled = true;

                    fetch(form.action, {
                        method: 'POST',
                        headers: { 'X-Requested-With': 'XMLHttpRequest' },
                        body: new FormData(form),
                    })
                        .then(function (r) {
                            if (!r.ok) throw new Error('send failed');
                            return r.text();
                        })
                        .then(function (html) {
                            messagesEl.innerHTML = html;
                            bodyInput.value = '';
                            bodyInput.focus();
                            messagesEl.scrollTop = messagesEl.scrollHeight;
                        })
                        .catch(function () {
                            alert('Could not send the message — please try again.');
                        })
                        .finally(function () {
                            sendBtn.disabled = false;
                        });
                });

                // Land already scrolled to the latest message.
                messagesEl.scrollTop = messagesEl.scrollHeight;
            })();
        </script>
    @endif

</x-layouts.customer-layout>
