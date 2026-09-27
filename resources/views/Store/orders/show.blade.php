<x-layouts.store-layout title="Order {{ $order->order_number }}">
    @if (session('status'))<div class="alert alert-success">{{ session('status') }}</div>@endif
    @if ($errors->any())<div class="alert alert-danger">{{ $errors->first() }}</div>@endif
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div><h4 class="header-title mb-1">Order {{ $order->order_number }}</h4><p class="text-muted mb-0">Placed {{ $order->created_at->format('d M Y, h:i A') }}</p></div>
        <a href="{{ route('store.orders.index') }}" class="btn btn-light">Back to orders</a>
    </div>
    <div class="row">
        <div class="col-lg-8">
            <div class="card"><div class="card-body">
                <h5>Items</h5>
                <div class="table-responsive"><table class="table align-middle"><thead><tr><th>Product</th><th>Unit price</th><th>Qty</th><th class="text-end">Total</th></tr></thead><tbody>
                    @foreach ($order->items as $item)
                        <tr><td>{{ $item['name'] ?? 'Item' }}<br><small class="text-muted">{{ $item['manufacturer'] ?? '' }}</small></td><td>₹{{ number_format((float) ($item['price'] ?? 0), 2) }}</td><td>{{ $item['quantity'] ?? 1 }}</td><td class="text-end">₹{{ number_format((float) ($item['price'] ?? 0) * (int) ($item['quantity'] ?? 1), 2) }}</td></tr>
                    @endforeach
                </tbody><tfoot><tr><th colspan="3" class="text-end">Subtotal</th><th class="text-end">₹{{ number_format((float) $order->subtotal, 2) }}</th></tr></tfoot></table></div>
            </div></div>
            <div class="card"><div class="card-body"><h5>Delivery address</h5><div class="text-muted">{{ $order->delivery_address }}</div>@if($order->latitude !== null && $order->longitude !== null)<small class="text-muted d-block mt-2">Coordinates: {{ $order->latitude }}, {{ $order->longitude }}</small>@endif</div></div>
        </div>
        <div class="col-lg-4">
            <div class="card"><div class="card-body"><h5>Customer</h5><p class="mb-0"><strong>{{ trim(($order->customer?->first_name ?? '').' '.($order->customer?->second_name ?? '')) ?: 'Customer' }}</strong><br>{{ $order->customer?->mobile }}<br>{{ $order->customer?->email }}</p></div></div>
            <div class="card"><div class="card-body"><h5>Payment</h5><p class="mb-0">{{ strtoupper($order->payment_method) }} · {{ ucfirst($order->payment_status) }}<br><small class="text-muted">{{ ucfirst(str_replace('_', ' ', $order->status)) }}</small></p></div></div>
            <div class="card"><div class="card-body">
                <h5>Order processing</h5><p>Status: <strong>{{ ucfirst($order->fulfillment_status) }}</strong></p>
                @if($order->fulfillment_remark)<div class="alert alert-light">{{ $order->fulfillment_remark }}</div>@endif
                @if($order->fulfillment_status === 'pending' && $canProcessOrders)
                    <form method="POST" action="{{ route('store.orders.accept', $order) }}" class="mb-3">
                        @csrf
                        <label class="form-label" for="accept-remark">Acceptance remark (optional)</label>
                        <textarea id="accept-remark" name="remark" class="form-control mb-2" rows="3" maxlength="2000"></textarea>
                        <button class="btn btn-success" type="submit">Accept order</button>
                    </form>
                    <hr>
                    <form method="POST" action="{{ route('store.orders.reject', $order) }}">
                        @csrf
                        <label class="form-label" for="reject-remark">Rejection remark (required)</label>
                        <textarea id="reject-remark" name="remark" class="form-control mb-2" rows="3" maxlength="2000" required></textarea>
                        <button class="btn btn-danger" type="submit">Reject order</button>
                    </form>
                @elseif($order->fulfillment_status === 'accepted' && $canAssignCaptains)
                    @if($captains->isNotEmpty())
                        <form method="POST" action="{{ route('store.orders.assign-captain', $order) }}">
                            @csrf
                            <label class="form-label" for="captain_id">Assign captain</label>
                            <select id="captain_id" name="captain_id" class="form-select mb-2" required>
                                <option value="">Choose a captain</option>
                                @foreach($captains as $captain)
                                    <option value="{{ $captain->id }}" @selected($order->captain_id === $captain->id)>{{ $captain->first_name }} {{ $captain->second_name }} · {{ $captain->mobile }}</option>
                                @endforeach
                            </select>
                            <button class="btn btn-primary" type="submit">{{ $order->captain_id ? 'Update captain' : 'Assign captain' }}</button>
                        </form>
                    @else
                        <p class="text-muted mb-0">No active captains are assigned to this store.</p>
                    @endif
                @elseif($order->fulfillment_status === 'accepted')
                    <p class="text-muted mb-0">Captain assignment requires an approved store.</p>
                @elseif($order->fulfillment_status === 'pending')
                    <p class="text-muted mb-0">Your store must be approved to process orders.</p>
                @elseif($order->fulfillment_status === 'rejected')
                    <p class="text-muted mb-0">This order has been rejected.</p>
                @else
                    <p class="text-muted mb-0">No further action is available for this order.</p>
                @endif
            </div></div>
        </div>
    </div>
</x-layouts.store-layout>
