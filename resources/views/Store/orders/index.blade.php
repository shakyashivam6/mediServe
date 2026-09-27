<x-layouts.store-layout title="Customer Orders">
    <div class="card">
        <div class="card-body">
            <h4 class="header-title mb-1">Customer Orders</h4>
            <p class="text-muted mb-3">Storefront orders for your store.</p>
            <div class="table-responsive">
                <table class="table table-striped align-middle">
                    <thead><tr><th>Order</th><th>Customer</th><th>Items</th><th>Amount</th><th>Payment</th><th>Order status</th><th>Captain</th><th>Placed</th><th>Delivery address</th><th></th></tr></thead>
                    <tbody>
                        @forelse ($orders as $order)
                            <tr>
                                <td>{{ $order->order_number }}</td>
                                <td>{{ trim(($order->customer?->first_name ?? '').' '.($order->customer?->second_name ?? '')) ?: 'Customer' }}<br><small>{{ $order->customer?->mobile }}</small></td>
                                <td>{{ collect($order->items)->map(fn ($item) => ($item['name'] ?? 'Item').' × '.($item['quantity'] ?? 1))->join(', ') }}</td>
                                <td>₹{{ number_format((float) $order->subtotal, 2) }}</td>
                                <td>{{ strtoupper($order->payment_method) }} · {{ ucfirst($order->payment_status) }}</td>
                                <td>{{ ucfirst(str_replace('_', ' ', $order->fulfillment_status)) }}<br><small class="text-muted">Payment: {{ ucfirst(str_replace('_', ' ', $order->status)) }}</small>@if($order->fulfillment_remark)<br><small>{{ $order->fulfillment_remark }}</small>@endif</td>
                                <td>{{ $order->captain ? trim($order->captain->first_name.' '.$order->captain->second_name) : 'Unassigned' }}</td>
                                <td>{{ $order->created_at->format('d M Y, h:i A') }}</td>
                                <td><details><summary>View address</summary><div class="mt-2">{{ $order->delivery_address }}</div></details></td>
                                <td><a class="btn btn-primary btn-sm" href="{{ route('store.orders.show', $order) }}">View order</a></td>
                            </tr>
                        @empty
                            <tr><td colspan="10" class="text-center text-muted py-4">No customer orders yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $orders->links() }}
        </div>
    </div>
</x-layouts.store-layout>
