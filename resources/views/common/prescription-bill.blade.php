{{--
    Delivery bill / invoice — rendered to PDF via dompdf (see
    PrescriptionBillController), so this stays plain, table-based HTML
    rather than flex/grid; dompdf's CSS support is limited to roughly
    IE8-era layout, and border-radius is kept modest since it renders
    inconsistently on table cells specifically. Shared by all three parties
    on a delivery (Store, Captain, Customer link here), same reasoning as
    common/sidenav.blade.php being one file for every role. Colours reuse
    the same brand palette as the Customer layout's own CSS custom
    properties (resources/views/components/layouts/customer-layout.blade.php)
    so a bill looks like it belongs to the same product.
--}}
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>Bill #{{ $prescription->id }} | {{ config('app.name', 'MediServe') }}</title>
    <style>
        {{--
            dompdf's core "Helvetica"/Arial fonts are the standard 14 PDF
            fonts — no embedded glyphs at all, so a non-Latin-1 character
            like ₹ (U+20B9) silently falls back to "?". DejaVu Sans is
            bundled with dompdf itself and does have the Rupee glyph, so
            it's used here instead — this is the actual fix, not a display
            quirk in the app itself (the same ₹ renders fine everywhere
            else, which is plain HTML/browser text, not a generated PDF).
        --}}
        * { box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', sans-serif; color: #1e293b; font-size: 12px; margin: 0; padding: 0; }

        .topbar { background: #2563eb; height: 7px; width: 100%; }
        .sheet { padding: 26px 34px 20px; }

        .header-table { width: 100%; border-collapse: collapse; margin-bottom: 22px; }
        .header-table td { vertical-align: top; }
        .brand { font-size: 22px; font-weight: bold; color: #0f172a; }
        .brand .dot { color: #2563eb; }
        .tagline { font-size: 10px; color: #94a3b8; margin-top: 3px; letter-spacing: .03em; }

        .bill-box { border: 1px solid #e2e8f0; border-radius: 8px; background: #f8fafc; padding: 12px 16px; width: 210px; display: inline-block; text-align: left; }
        .bill-box .title { font-size: 13px; font-weight: bold; color: #2563eb; text-transform: uppercase; letter-spacing: .04em; margin-bottom: 8px; }
        .bill-box .row { font-size: 11px; color: #475569; margin-top: 4px; }
        .bill-box .row strong { color: #0f172a; }
        .bill-box .status-row { margin-top: 8px; }

        .parties-table { width: 100%; border-collapse: separate; border-spacing: 8px 0; margin: 0 0 20px; }
        .parties-table td { width: 33.33%; vertical-align: top; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 12px 14px; }
        .parties-table h4 { margin: 0 0 8px; font-size: 10px; text-transform: uppercase; letter-spacing: .06em; color: #2563eb; font-weight: bold; border-bottom: 1px solid #e2e8f0; padding-bottom: 6px; }
        .parties-table p { margin: 0 0 3px; color: #475569; }
        .party-name { font-weight: bold; color: #0f172a; font-size: 12.5px; }

        .section-label { font-size: 10px; text-transform: uppercase; letter-spacing: .06em; color: #64748b; font-weight: bold; margin: 0 0 8px; }

        table.items { width: 100%; border-collapse: collapse; margin-bottom: 4px; }
        table.items thead th { background: #2563eb; color: #ffffff; text-align: left; padding: 9px 10px; font-size: 10px; text-transform: uppercase; letter-spacing: .04em; }
        table.items thead th:first-child { border-top-left-radius: 6px; border-bottom-left-radius: 6px; }
        table.items thead th:last-child { border-top-right-radius: 6px; border-bottom-right-radius: 6px; }
        table.items tbody td { padding: 8px 10px; border-bottom: 1px solid #e2e8f0; color: #334155; }
        table.items tbody tr:nth-child(even) td { background: #f8fafc; }
        table.items td.num, table.items th.num { text-align: right; }

        .totals-wrap { width: 100%; margin-top: 14px; }
        .totals-table { width: 260px; float: right; border-collapse: collapse; }
        .totals-table td { padding: 6px 4px; font-size: 12px; color: #475569; }
        .totals-table td.val { text-align: right; }
        .totals-table .grand { background: #eff6ff; }
        .totals-table .grand td { border-top: 2px solid #2563eb; font-weight: bold; font-size: 15px; color: #1d4ed8; padding: 10px 10px; }
        .totals-table .grand td.label { border-top: 2px solid #2563eb; border-top-left-radius: 6px; border-bottom-left-radius: 6px; }
        .totals-table .grand td.val { border-top: 2px solid #2563eb; border-top-right-radius: 6px; border-bottom-right-radius: 6px; }
        .clearfix { clear: both; }

        .badge { display: inline-block; padding: 3px 10px; border-radius: 10px; font-size: 9.5px; font-weight: bold; letter-spacing: .02em; }
        .badge-prepaid { background: #dbeafe; color: #1d4ed8; }
        .badge-cod-pending { background: #fee2e2; color: #991b1b; }
        .badge-cod-collected { background: #fef3c7; color: #92400e; }
        .badge-cod-settled { background: #dcfce7; color: #166534; }

        .footer { margin-top: 30px; padding-top: 14px; border-top: 1px solid #e2e8f0; text-align: center; }
        .footer .thanks { font-size: 12px; font-weight: bold; color: #0f172a; margin-bottom: 4px; }
        .footer .fine-print { font-size: 9.5px; color: #94a3b8; }
    </style>
</head>
<body>

    <div class="topbar"></div>

    <div class="sheet">

        <table class="header-table">
            <tr>
                <td>
                    <div class="brand"><span class="dot">●</span> {{ config('app.name', 'MediServe') }}</div>
                    <div class="tagline">SECURE &middot; SCALABLE &middot; SIMPLIFIED</div>
                </td>
                <td style="width: 240px; text-align: right;">
                    <div class="bill-box">
                        <div class="title">Delivery Bill</div>
                        <div class="row">Bill No: <strong>{{ $prescription->order_number ?? '#'.$prescription->id }}</strong></div>
                        @if ($prescription->prescription_number)
                            <div class="row">Prescription ID: <strong>{{ $prescription->prescription_number }}</strong></div>
                        @endif
                        <div class="row">Date: <strong>{{ ($prescription->delivered_at ?? $prescription->created_at)->format('d M Y, h:i A') }}</strong></div>
                        <div class="row status-row">
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
                    </div>
                </td>
            </tr>
        </table>
        <div class="clearfix"></div>

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

        <p class="section-label">Order Items</p>
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
                    <tr><td colspan="4" style="color:#94a3b8; padding: 10px;">No itemised medicines on record for this order.</td></tr>
                @endforelse
            </tbody>
        </table>

        <div class="totals-wrap">
            <table class="totals-table">
                <tr class="grand">
                    <td class="label">Total Amount</td>
                    <td class="val">₹{{ number_format((float) $prescription->total_amount, 2) }}</td>
                </tr>
            </table>
            <div class="clearfix"></div>
        </div>

        <div class="footer">
            <div class="thanks">Thank you for choosing {{ config('app.name', 'MediServe') }}!</div>
            <div class="fine-print">This is a system-generated delivery bill for prescription #{{ $prescription->id }} and does not require a signature.</div>
        </div>

    </div>

</body>
</html>
