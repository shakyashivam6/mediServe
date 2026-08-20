<x-layouts.customer-layout title="My Prescriptions">

    <div style="display:flex; align-items:center; justify-content:space-between; gap:10px; margin-bottom:16px;">
        <h2 style="margin:0;">My Prescriptions</h2>
        <a href="{{ route('customer.prescriptions.create') }}" class="btn" style="width:auto; margin:0;">+ Upload New</a>
    </div>

    @if ($prescriptions->isEmpty())
        <div class="card" style="text-align:center;">
            <p style="color:var(--ink-soft); margin-top:0;">You haven't uploaded a prescription yet.</p>
            <a href="{{ route('customer.prescriptions.create') }}" class="btn">Upload your first prescription</a>
        </div>
    @else
        @foreach ($prescriptions as $prescription)
            <a href="{{ route('customer.prescriptions.show', $prescription) }}" style="text-decoration:none; color:inherit; display:block;">
                <div class="card" style="display:flex; align-items:center; justify-content:space-between; gap:10px;">
                    <div>
                        <div style="font-weight:700; font-size:14px;">{{ $prescription->prescription_number ?? 'Prescription #'.$prescription->id }}</div>
                        <div class="hint" style="margin-top:4px;">{{ $prescription->created_at->format('d M Y, h:i A') }}</div>
                        @if ($prescription->remark)
                            <div class="hint" style="margin-top:4px;">{{ \Illuminate\Support\Str::limit($prescription->remark, 60) }}</div>
                        @endif
                        @if ($prescription->isCodAmountPending())
                            <div style="margin-top:4px; font-size:12px; font-weight:700; color:var(--red);">💰 ₹{{ number_format((float) $prescription->total_amount, 2) }} due on delivery</div>
                        @endif
                    </div>
                    <span class="status-badge status-{{ $prescription->status }}">{{ $prescription->customerStatusLabel() }}</span>
                </div>
            </a>
        @endforeach
    @endif

</x-layouts.customer-layout>
