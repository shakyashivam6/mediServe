<x-layouts.store-layout title="COD Settlements">

    @if (session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger">{{ $errors->first() }}</div>
    @endif

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title mb-1">Pending Settlement</h4>
                    <p class="text-muted font-14 mb-3">
                        Cash your Captains have collected on delivery but haven't physically handed back to you yet.
                        Settle a Captain once you've actually received their cash.
                    </p>

                    @if ($pendingByCaptain->isEmpty())
                        <p class="text-muted mb-0">Nothing pending settlement right now.</p>
                    @else
                        @foreach ($pendingByCaptain as $captainId => $orders)
                            @php $captain = $orders->first()->captain; @endphp
                            <div class="border rounded p-3 mb-3">
                                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-2">
                                    <div>
                                        <strong>{{ $captain?->first_name }} {{ $captain?->second_name }}</strong>
                                        <span class="text-muted ms-2">{{ $orders->count() }} order(s)</span>
                                    </div>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="fs-18 fw-bold text-warning">₹{{ number_format((float) $orders->sum('total_amount'), 2) }}</span>
                                        <form method="POST" action="{{ route('store.settlements.settle', $captainId) }}" onsubmit="return confirm('Confirm you have physically received this cash from {{ $captain?->first_name }}?');">
                                            @csrf
                                            <button type="submit" class="btn btn-success btn-sm">
                                                <i class="ri-check-double-line align-middle"></i> Mark Settled
                                            </button>
                                        </form>
                                    </div>
                                </div>
                                <table class="table table-sm mb-0">
                                    <thead>
                                        <tr>
                                            <th>Order</th>
                                            <th>Customer</th>
                                            <th>Delivered</th>
                                            <th class="text-end">Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($orders as $order)
                                            <tr>
                                                <td>
                                                    <a href="{{ route('store.prescriptions.show', $order) }}" target="_blank" class="fw-semibold">
                                                        #{{ $order->id }}
                                                    </a>
                                                </td>
                                                <td>{{ $order->customer->first_name }} {{ $order->customer->second_name }}</td>
                                                <td class="text-muted">{{ $order->delivered_at?->format('d M, h:i A') }}</td>
                                                <td class="text-end">₹{{ number_format((float) $order->total_amount, 2) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title mb-3">Recently Settled</h4>

                    @if ($settledRecently->isEmpty())
                        <p class="text-muted mb-0">Nothing settled yet.</p>
                    @else
                        <table class="table table-sm mb-0">
                            <thead>
                                <tr>
                                    <th>Order</th>
                                    <th>Captain</th>
                                    <th>Customer</th>
                                    <th>Settled</th>
                                    <th class="text-end">Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($settledRecently as $order)
                                    <tr>
                                        <td>
                                            <a href="{{ route('store.prescriptions.show', $order) }}" target="_blank" class="fw-semibold">
                                                #{{ $order->id }}
                                            </a>
                                        </td>
                                        <td>{{ $order->captain?->first_name }} {{ $order->captain?->second_name }}</td>
                                        <td>{{ $order->customer->first_name }} {{ $order->customer->second_name }}</td>
                                        <td>{{ $order->settled_at?->format('d M, h:i A') }}</td>
                                        <td class="text-end">₹{{ number_format((float) $order->total_amount, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>
    </div>

</x-layouts.store-layout>
