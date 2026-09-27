<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Your orders · MediServe</title>
    <style>
        *{box-sizing:border-box}body{margin:0;background:#f7faf8;color:#18332c;font:15px/1.5 Inter,ui-sans-serif,system-ui,sans-serif}.top{background:#073d31;color:#eafff7;text-align:center;padding:8px;font-size:12px}.wrap{width:min(960px,calc(100% - 32px));margin:auto}.head{background:#fff;border-bottom:1px solid #e6eeeb}.headin{height:70px;display:flex;align-items:center;justify-content:space-between}.brand{font-size:23px;font-weight:850;letter-spacing:-1px;color:#087f5b;text-decoration:none}.brand span{color:#18332c}a{color:#087f5b;text-decoration:none}.title{margin:30px 0 18px}.title h1{margin:0;font-size:30px}.title p,.muted{color:#70817c}.panel{background:#fff;border:1px solid #e6eeeb;border-radius:15px;padding:20px;margin:14px 0}.order-head{display:flex;align-items:flex-start;justify-content:space-between;gap:15px;border-bottom:1px solid #edf2ef;padding-bottom:13px}.order-number{font-weight:800}.date{font-size:13px;color:#70817c}.badge{display:inline-block;padding:5px 10px;border-radius:20px;background:#e7f5ee;color:#087f5b;font-size:12px;font-weight:750}.badge.pending{background:#fff4dd;color:#925b0e}.badge.failed{background:#feeceb;color:#a3322c}.items{padding:7px 0}.item{display:flex;justify-content:space-between;gap:16px;padding:8px 0}.item-name{font-weight:650}.muted{font-size:13px}.summary{display:flex;align-items:center;justify-content:space-between;border-top:1px solid #edf2ef;padding-top:13px;font-weight:750}.meta{display:flex;flex-wrap:wrap;gap:15px;margin:12px 0;color:#64766f;font-size:13px}.actions{display:flex;justify-content:flex-end;margin-top:12px}.button{display:inline-block;padding:9px 14px;background:#087f5b;color:#fff;border-radius:8px;font-weight:700}.empty{text-align:center;padding:48px 20px}.empty h2{margin:0 0 5px}.empty p{color:#70817c}.pagination{margin:22px 0}.pagination nav{display:flex;justify-content:center;gap:5px}.pagination a,.pagination span{padding:7px 11px;border:1px solid #e6eeeb;border-radius:7px;background:#fff}.pagination [aria-current=page] span{background:#087f5b;color:#fff;border-color:#087f5b}@media(max-width:600px){.panel{padding:16px}.order-head{align-items:flex-start}.title{margin-top:23px}}
    </style>
</head>
<body>
    <div class="top">Your health essentials, delivered with care · Secure mobile OTP</div>
    <header class="head"><div class="wrap headin"><a class="brand" href="{{ route('home') }}">medi<span>Serve</span></a><a href="{{ route('home') }}">Continue shopping</a></div></header>
    <main class="wrap">
        <div class="title"><h1>Your orders</h1><p>Track your orders and review payment status.</p></div>
        @forelse($orders as $order)
            @php
                $badgeClass = in_array($order->status, ['payment_failed', 'rejected'], true)
                    ? 'failed'
                    : ($order->status === 'payment_pending' ? 'pending' : '');
                $orderNumber = $order->history_type === 'prescription'
                    ? ($order->order_number ?: $order->prescription_number)
                    : $order->order_number;
            @endphp
            <article class="panel" @if($order->history_type === 'prescription') id="order-prescription-{{ $order->id }}" @endif>
                <div class="order-head">
                    <div>
                        <div class="order-number">Order {{ $orderNumber }}</div>
                        <div class="date">Placed {{ $order->created_at->format('d M Y, h:i A') }}</div>
                    </div>
                    <span class="badge {{ $badgeClass }}">{{ $order->history_status_label }}</span>
                </div>
                <div class="items">
                    @foreach(($order->items ?? []) as $item)
                        <div class="item">
                            <span><span class="item-name">{{ $item['name'] ?? 'Medicine' }}</span><br><span class="muted">Qty {{ $item['quantity'] ?? 1 }}</span></span>
                            <strong>₹{{ number_format((float) ($item['price'] ?? 0) * (int) ($item['quantity'] ?? 1), 2) }}</strong>
                        </div>
                    @endforeach
                </div>
                <div class="meta">
                    @if($order->history_type === 'prescription')
                        <span>Source: <a href="{{ route('customer.prescriptions.show', $order) }}">Prescription upload</a></span>
                    @else
                        <span>Source: Cart</span>
                    @endif
                    <span>Payment: {{ $order->history_payment_label }}</span>
                    <span>Delivery: {{ Str::limit($order->delivery_address, 90) }}</span>
                </div>
                @if($order->history_remark)
                    <div class="meta" style="margin-top:0"><span>Store remark: {{ $order->history_remark }}</span></div>
                @endif
                @if((float) ($order->history_discount ?? 0) > 0)
                    <div class="meta" style="margin-top:0;color:#087f5b"><span>Instant discount: −₹{{ number_format((float) $order->history_discount, 2) }}</span></div>
                @endif
                <div class="summary"><span>Total</span><span>₹{{ number_format((float) $order->history_total, 2) }}</span></div>
                @if($order->history_url)
                    <div class="actions"><a class="button" href="{{ $order->history_url }}">View order</a></div>
                @endif
            </article>
        @empty
            <section class="panel empty"><h2>No orders yet</h2><p>Your placed orders will appear here.</p><a class="button" href="{{ route('home') }}">Browse medicines</a></section>
        @endforelse
        @if($orders->hasPages())<div class="pagination">{{ $orders->links() }}</div>@endif
    </main>
    <x-storefront-footer />
</body>
</html>
