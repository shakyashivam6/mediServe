<x-layouts.customer-layout title="Your addresses">
    <div class="card">
        <h2 style="margin-top:0">Your addresses</h2>
        <p style="color:var(--ink-soft);font-size:14px">Saved delivery locations linked to your account.</p>

        @forelse($addresses as $address)
            <section style="padding:16px 0;border-top:1px solid var(--line)">
                <div style="display:flex;justify-content:space-between;gap:12px;align-items:center">
                    <strong>{{ $address->label }} @if($address->is_default)<span class="status-badge status-confirmed">Default</span>@endif</strong>
                    @if($address->latitude && $address->longitude)
                        <a href="https://www.google.com/maps?q={{ $address->latitude }},{{ $address->longitude }}" target="_blank" rel="noopener">View map</a>
                    @endif
                </div>
                <div style="margin-top:8px">{{ $address->recipient_name }}<br>{{ $address->mobile }}<br>{{ $address->address_line }}<br>{{ $address->pincode }}</div>
            </section>
        @empty
            <section style="padding:16px 0;border-top:1px solid var(--line)">
                <strong>Profile address</strong>
                @if(filled(auth()->user()->address_line))
                    <div style="margin-top:8px">{{ auth()->user()->first_name }} {{ auth()->user()->second_name }}<br>{{ auth()->user()->mobile }}<br>{{ auth()->user()->address_line }}<br>{{ auth()->user()->pincode }}</div>
                @else
                    <p style="color:var(--ink-soft)">No address saved yet.</p>
                @endif
            </section>
        @endforelse

        <a class="btn btn-soft" href="{{ route('checkout') }}">Add or choose an address at checkout</a>
    </div>
</x-layouts.customer-layout>
