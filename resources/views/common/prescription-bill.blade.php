{{--
    Delivery bill / invoice — rendered to PDF via dompdf (see
    PrescriptionBillController), so this stays plain, table-based HTML
    rather than flex/grid; dompdf's CSS support is limited to roughly
    IE8-era layout. Shared by all three parties on a delivery (Store,
    Captain, Customer link here), same reasoning as common/sidenav.blade.php
    being one file for every role.
--}}
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>Bill #{{ $prescription->id }} | {{ config('app.name', 'MediServe') }}</title>
    <style>
        body { font-family: 'Helvetica', Arial, sans-serif; color: #1e293b; font-size: 12px; margin: 0; padding: 30px; }
        .header-table { width: 100%; border-collapse: collapse; margin-bottom: 18px; }
        .header-table td { vertical-align: top; }
        .brand { font-size: 20px; font-weight: bold; color: #2563eb; }
        .brand .dot { color: #16a34a; }
        .tagline { font-size: 10px; color: #64748b; margin-top: 2px; }
        .bill-meta { text-align: right; font-size: 12px; }
        .bill-meta .title { font-size: 16px; font-weight: bold; color: #0f172a; }
        .bill-meta .row { margin-top: 3px; color: #475569; }

        .parties-table { width: 100%; border-collapse: collapse; margin-bottom: 18px; }
        .parties-table td { width: 33.33%; vertical-align: top; border: 1px solid #e2e8f0; padding: 10px; }
        .parties-table h4 { margin: 0 0 6px; font-size: 11px; text-transform: uppercase; letter-spacing: .03em; color: #64748b; }
        .parties-table p { margin: 0 0 2px; }
        .party-name { font-weight: bold; color: #0f172a; }

        table.items { width: 100%; border-collapse: collapse; margin-bottom: 14px; }
        table.items th { background: #f1f5f9; text-align: left; padding: 7px 8px; font-size: 10.5px; text-transform: uppercase; color: #475569; border-bottom: 1px solid #e2e8f0; }
        table.items td { padding: 7px 8px; border-bottom: 1px solid #f1f5f9; }
        table.items td.num, table.items th.num { text-align: right; }

        .totals-table { width: 260px; margin-left: auto; border-collapse: collapse; }
        .totals-table td { padding: 5px 8px; }
        .totals-table .grand td { border-top: 1px solid #0f172a; font-weight: bold; font-size: 14px; padding-top: 8px; }

        .badge { display: inline-block; padding: 3px 9px; border-radius: 10px; font-size: 10px; font-weight: bold; }
        .badge-prepaid { background: #dbeafe; color: #1d4ed8; }
        .badge-cod-pending { background: #fee2e2; color: #991b1b; }
        .badge-cod-collected { background: #fef3c7; color: #92400e; }
        .badge-cod-settled { background: #dcfce7; color: #166534; }

        .footer { margin-top: 26px; padding-top: 10px; border-top: 1px solid #e2e8f0; font-size: 10px; color: #94a3b8; text-align: center; }
    </style>
</head>
<body>

    <table class="header-table">
        <tr>
            <td>
                <div class="brand">{{ config('app.name', 'MediServe') }}<span class="dot">.</span></div>
                <div class="tagline">Secure. Scalable. Simplified.</div>
            </td>
            <td class="bill-meta">
                <div class="title">Delivery Bill</div>
                <div class="row">Bill No: <strong>#{{ $prescription->id }}</strong></div>
                <div class="row">Date: {{ ($prescription->delivered_at ?? $prescription->created_at)->format('d M Y, h:i A') }}</div>
                <div class="row">
                    @if ($prescription->payment_method === 'prepaid')
                        <span class="badge badge-prepaid">Prepaid</span>
                    @elseif ($prescription->payment_status === 'settled')
                        <span class="badge badge-cod-settled">COD &mdash; Settled</span>
                    @elseif ($prescription->payment_status === 'collected')
                        <span class="badge badge-cod-collected">COD &mdash; Collected</span>
                    @else
                        <span class="badge badge-cod-pending">COD &mdash; Pending</span>
                    @endif
                </div>
            </td>
        </tr>
    </table>

    <table class="parties-table">
        <tr>
            <td>
                <h4>Store</h4>
                <p class="party-name">{{ $prescription->store_profile?->shop_name ?? ($prescription->store?->first_name.' '.$prescription->store?->second_name) }}</p>
                <p>{{ $prescription->store?->mobile }}</p>
            </td>
            <td>
                <h4>Customer</h4>
                <p class="party-name">{{ $prescription->customer->first_name }} {{ $prescription->customer->second_name }}</p>
                <p>{{ $prescription->customer->mobile }}</p>
                <p>{{ $prescription->delivery_address }}</p>
            </td>
            <td>
                <h4>Captain</h4>
                <p class="party-name">{{ $prescription->captain->first_name }} {{ $prescription->captain->second_name }}</p>
                <p>{{ $prescription->captain->mobile }}</p>
                <p>{{ ucfirst($prescription->captain->vehicle_type ?? '—') }}</p>
            </td>
        </tr>
    </table>

    <table class="items">
        <thead>
            <tr>
                <th>Medicine</th>
                <th class="num">Qty</th>
                <th class="num">Price</th>
                <th class="num">Amount</th>
            </tr>
        </thead>
        <tbody>
            @forelse (($prescription->items ?? []) as $item)
                <tr>
                    <td>{{ $item['name'] ?? '—' }}</td>
                    <td class="num">{{ $item['quantity'] ?? '—' }}</td>
                    <td class="num">₹{{ number_format((float) ($item['price'] ?? 0), 2) }}</td>
                    <td class="num">₹{{ number_format((float) ($item['price'] ?? 0) * (float) ($item['quantity'] ?? 1), 2) }}</td>
                </tr>
            @empty
                <tr><td colspan="4" style="color:#94a3b8;">No itemised medicines on record for this order.</td></tr>
            @endforelse
        </tbody>
    </table>

    <table class="totals-table">
        <tr class="grand">
            <td>Total</td>
            <td class="num" style="text-align:right;">₹{{ number_format((float) $prescription->total_amount, 2) }}</td>
        </tr>
    </table>

    <div class="footer">
        This is a system-generated delivery bill for prescription #{{ $prescription->id }} — {{ config('app.name', 'MediServe') }}.
    </div>

</body>
</html>
