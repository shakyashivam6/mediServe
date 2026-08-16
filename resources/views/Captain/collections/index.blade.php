<x-layouts.captain-layout title="My Collections">

    <div class="row row-cols-1 row-cols-md-3">
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <h5 class="text-muted fs-13 fw-bold text-uppercase">Cash In Hand</h5>
                    <h3 class="my-2 py-1 fw-bold text-warning">₹{{ number_format((float) $cashInHand, 2) }}</h3>
                    <p class="mb-0 text-muted">Collected, not yet handed to your store</p>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <h5 class="text-muted fs-13 fw-bold text-uppercase">Settled (All Time)</h5>
                    <h3 class="my-2 py-1 fw-bold text-success">₹{{ number_format((float) $totalSettled, 2) }}</h3>
                    <p class="mb-0 text-muted">Already handed over &amp; confirmed by your store</p>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <h5 class="text-muted fs-13 fw-bold text-uppercase">Collected on {{ $date->format('d M Y') }}</h5>
                    <h3 class="my-2 py-1 fw-bold">₹{{ number_format((float) $codCollectedOnDate, 2) }}</h3>
                    <p class="mb-0 text-muted">{{ $deliveredOnDate->count() }} order(s) delivered this day</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
                        <h4 class="header-title mb-0">Deliveries — {{ $date->format('d M Y') }}</h4>
                        <form method="GET" action="{{ route('captain.collections.index') }}" class="d-flex gap-2">
                            <input type="date" name="date" value="{{ $date->format('Y-m-d') }}" class="form-control form-control-sm" max="{{ now()->format('Y-m-d') }}">
                            <button type="submit" class="btn btn-soft-primary btn-sm">Go</button>
                        </form>
                    </div>

                    @if ($deliveredOnDate->isEmpty())
                        <p class="text-muted mb-0">No deliveries on this day.</p>
                    @else
                        <div class="table-responsive">
                            <table class="table table-sm align-middle">
                                <thead>
                                    <tr>
                                        <th>Time</th>
                                        <th>Customer</th>
                                        <th>Payment</th>
                                        <th class="text-end">Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($deliveredOnDate as $prescription)
                                        <tr>
                                            <td>{{ $prescription->delivered_at?->format('h:i A') }}</td>
                                            <td>{{ $prescription->customer->first_name }} {{ $prescription->customer->second_name }}</td>
                                            <td>{{ $prescription->paymentStatusLabel() }}</td>
                                            <td class="text-end">₹{{ number_format((float) $prescription->total_amount, 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

</x-layouts.captain-layout>
