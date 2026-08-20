<x-layouts.captain-layout title="Dashboard">

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
                    <h4 class="header-title mb-1">To Deliver</h4>
                    <p class="text-muted font-14 mb-3">Orders assigned to you, out for delivery.</p>

                    @if ($toDeliver->isEmpty())
                        <p class="text-muted mb-0">Nothing out for delivery right now.</p>
                    @else
                        @foreach ($toDeliver as $prescription)
                            <div class="border rounded p-3 mb-3">
                                <div class="d-flex justify-content-between flex-wrap gap-2 mb-2">
                                    <div>
                                        <strong>{{ $prescription->customer->first_name }} {{ $prescription->customer->second_name }}</strong>
                                        <a href="tel:{{ $prescription->customer->mobile }}" class="ms-2 text-muted">
                                            <i class="ri-phone-line align-middle"></i> {{ $prescription->customer->mobile }}
                                        </a>
                                    </div>
                                    <span class="badge bg-{{ $prescription->payment_method === 'cod' ? 'warning' : 'info' }}">
                                        {{ $prescription->paymentStatusLabel() }}
                                        @if ($prescription->total_amount !== null)
                                            &middot; ₹{{ number_format((float) $prescription->total_amount, 2) }}
                                        @endif
                                    </span>
                                </div>
                                <p class="text-muted mb-2">{{ $prescription->delivery_address }}</p>

                                @if ($prescription->googleMapsUrl())
                                    <a href="{{ $prescription->googleMapsUrl() }}" target="_blank" class="btn btn-soft-primary btn-sm mb-2">
                                        <i class="ri-map-pin-line align-middle"></i> Open in Maps
                                    </a>
                                @endif

                                <form method="POST" action="{{ route('captain.deliveries.deliver', $prescription) }}" class="d-flex align-items-center flex-wrap gap-2 mt-2">
                                    @csrf
                                    @if ($prescription->isCod())
                                        <div class="form-check">
                                            <input type="checkbox" name="collected" value="1" class="form-check-input" id="collected-{{ $prescription->id }}"
                                                onchange="document.getElementById('deliver-btn-{{ $prescription->id }}').disabled = !this.checked">
                                            <label class="form-check-label" for="collected-{{ $prescription->id }}">
                                                Collected ₹{{ number_format((float) $prescription->total_amount, 2) }} cash
                                            </label>
                                        </div>
                                    @endif
                                    <button type="submit" id="deliver-btn-{{ $prescription->id }}" class="btn btn-success btn-sm" @if ($prescription->isCod()) disabled @endif>
                                        <i class="ri-check-line align-middle"></i> Mark Delivered
                                    </button>
                                </form>
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
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h4 class="header-title mb-0">Recently Delivered</h4>
                        <a href="{{ route('captain.collections.index') }}" class="btn btn-soft-primary btn-sm">Full Collection Report</a>
                    </div>

                    @if ($recentlyDelivered->isEmpty())
                        <p class="text-muted mb-0">No deliveries yet.</p>
                    @else
                        <table class="table table-sm mb-0">
                            <thead>
                                <tr>
                                    <th>Customer</th>
                                    <th>Delivered</th>
                                    <th>Payment</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($recentlyDelivered as $prescription)
                                    <tr>
                                        <td>{{ $prescription->customer->first_name }} {{ $prescription->customer->second_name }}</td>
                                        <td>{{ $prescription->delivered_at?->format('d M, h:i A') }}</td>
                                        <td>{{ $prescription->paymentStatusLabel() }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>
    </div>

</x-layouts.captain-layout>
