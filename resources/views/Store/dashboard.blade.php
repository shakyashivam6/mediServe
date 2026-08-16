<x-layouts.store-layout title="Dashboard">

    @if (session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    @php
        $variant = ['pending' => 'warning', 'approved' => 'success', 'rejected' => 'danger'][$store->status];
        $statusCopy = [
            'pending' => "Your store is awaiting Admin's approval. You'll be able to add Captains once it's approved — you can still fill in your profile in the meantime.",
            'approved' => 'Your store is approved and live.',
            'rejected' => "Your store's registration was rejected. Contact Admin for details.",
        ][$store->status];
    @endphp

    <div class="alert alert-{{ $variant }} d-flex align-items-center" role="alert">
        <iconify-icon icon="solar:shield-check-bold-duotone" class="fs-24 me-2"></iconify-icon>
        <div>
            <strong>Store status: {{ ucfirst($store->status) }}.</strong>
            {{ $statusCopy }}
        </div>
    </div>

    @if ($store->status === 'approved' && ($newRequestCount > 0 || $activeOrderCount > 0))
        <div class="alert alert-info d-flex align-items-center" role="alert">
            <iconify-icon icon="solar:bell-bing-bold-duotone" class="fs-24 me-2"></iconify-icon>
            <div>
                @if ($newRequestCount > 0)
                    <strong>{{ $newRequestCount }} new prescription {{ \Illuminate\Support\Str::plural('request', $newRequestCount) }}</strong> waiting to be claimed.
                @endif
                @if ($newRequestCount > 0 && $activeOrderCount > 0)
                    &nbsp;·&nbsp;
                @endif
                @if ($activeOrderCount > 0)
                    <strong>{{ $activeOrderCount }} order {{ \Illuminate\Support\Str::plural('request', $activeOrderCount) }}</strong> currently running.
                @endif
                <a href="{{ route('store.prescriptions.index') }}" class="link-offset-2 text-decoration-underline ms-1">Open Prescriptions &rarr;</a>
            </div>
        </div>
    @endif

    <div class="row row-cols-xxl-4 row-cols-md-2 row-cols-1">
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center gap-2 justify-content-between">
                        <div>
                            <h5 class="text-muted fs-13 fw-bold text-uppercase">Shop Name</h5>
                            <h3 class="my-2 py-1 fw-bold">{{ $store->shop_name }}</h3>
                            <p class="mb-0 text-muted">
                                <a href="{{ route('store.profile.edit') }}" class="link-offset-2 text-decoration-underline">Edit profile</a>
                            </p>
                        </div>
                        <div class="avatar-xl flex-shrink-0">
                            <span class="avatar-title bg-primary-subtle text-primary rounded-circle fs-42">
                                <iconify-icon icon="solar:shop-bold-duotone"></iconify-icon>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center gap-2 justify-content-between">
                        <div>
                            <h5 class="text-muted fs-13 fw-bold text-uppercase">My Captains</h5>
                            <h3 class="my-2 py-1 fw-bold">{{ $captainCount }}</h3>
                            <p class="mb-0 text-muted">{{ $activeCaptainCount }} active</p>
                        </div>
                        <div class="avatar-xl flex-shrink-0">
                            <span class="avatar-title bg-warning-subtle text-warning rounded-circle fs-42">
                                <iconify-icon icon="solar:scooter-bold-duotone"></iconify-icon>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center gap-2 justify-content-between">
                        <div>
                            <h5 class="text-muted fs-13 fw-bold text-uppercase">Delivery Radius</h5>
                            <h3 class="my-2 py-1 fw-bold">{{ $store->delivery_radius_km ? $store->delivery_radius_km.' km' : '—' }}</h3>
                            <p class="mb-0 text-muted">{{ $store->delivery_speed_kmph ? $store->delivery_speed_kmph.' km/h speed' : 'Not set' }}</p>
                        </div>
                        <div class="avatar-xl flex-shrink-0">
                            <span class="avatar-title bg-success-subtle text-success rounded-circle fs-42">
                                <iconify-icon icon="solar:point-on-map-bold-duotone"></iconify-icon>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center gap-2 justify-content-between">
                        <div>
                            <h5 class="text-muted fs-13 fw-bold text-uppercase">Location</h5>
                            <h3 class="my-2 py-1 fw-bold">{{ $store->latitude ? 'Set' : 'Not set' }}</h3>
                            <p class="mb-0 text-muted">
                                <a href="{{ route('store.profile.edit') }}" class="link-offset-2 text-decoration-underline">Pick on map</a>
                            </p>
                        </div>
                        <div class="avatar-xl flex-shrink-0">
                            <span class="avatar-title bg-info-subtle text-info rounded-circle fs-42">
                                <iconify-icon icon="solar:map-point-wave-bold-duotone"></iconify-icon>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex flex-wrap align-items-center gap-2">
                    <h4 class="header-title me-auto">Orders Claimed — Last 14 Days</h4>
                </div>
                <div class="card-body">
                    <div dir="ltr" class="px-2">
                        <div id="store-claim-trend-chart" class="apex-charts" data-colors="#0acf97"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xxl-4">
            <div class="card">
                <div class="d-flex card-header justify-content-between align-items-center">
                    <h4 class="header-title">Recent Orders</h4>
                    <a href="{{ route('store.prescriptions.index') }}" class="btn btn-sm btn-light">View All <i class="ri-arrow-right-line ms-1"></i></a>
                </div>
                <div class="card-body p-0">
                    <div class="bg-light bg-opacity-50 py-1 text-center">
                        <p class="m-0"><b>{{ $activeOrderCount }}</b> running out of <span class="fw-medium">{{ $totalOrderCount }}</span> claimed</p>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-custom table-centered table-sm table-nowrap table-hover mb-0">
                            <tbody>
                                @forelse ($recentPrescriptions as $order)
                                    <tr>
                                        <td>
                                            <a href="{{ route('store.prescriptions.show', $order) }}" class="text-reset">
                                                <span class="text-muted fs-12">Prescription</span> <br>
                                                <h5 class="fs-14 mt-1">#{{ $order->id }}</h5>
                                            </a>
                                        </td>
                                        <td>
                                            <span class="text-muted fs-12">Date</span> <br>
                                            <h5 class="fs-14 mt-1 fw-normal">{{ $order->created_at->format('d M Y') }}</h5>
                                        </td>
                                        <td>
                                            <span class="text-muted fs-12">Amount</span> <br>
                                            <h5 class="fs-14 mt-1 fw-normal">{{ $order->total_amount !== null ? '₹'.number_format((float) $order->total_amount, 2) : '—' }}</h5>
                                        </td>
                                        <td>
                                            <span class="text-muted fs-12">Status</span> <br>
                                            <h5 class="fs-14 mt-1 fw-normal"><i class="ri-circle-fill fs-12 text-{{ $statusVariant[$order->status] }}"></i> {{ ucfirst(str_replace('_', ' ', $order->status)) }}</h5>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-center text-muted py-4">No orders claimed yet.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="text-muted text-center text-sm-start">
                        Showing <span class="fw-semibold">{{ $recentPrescriptions->count() }}</span> of <span class="fw-semibold">{{ $totalOrderCount }}</span> orders
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xxl-4">
            <div class="card card-h-100">
                <div class="card-header d-flex flex-wrap align-items-center gap-2">
                    <h4 class="header-title me-auto">Recent Customers</h4>
                </div>
                <div class="card-body p-0">
                    <div class="bg-light bg-opacity-50 py-1 text-center">
                        <p class="m-0">Most recently active customers on your orders</p>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-custom table-centered table-sm table-nowrap table-hover mb-0">
                            <tbody>
                                @forelse ($recentCustomerOrders as $order)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-md flex-shrink-0 me-2">
                                                    <span class="avatar-title bg-primary-subtle text-primary rounded-circle">
                                                        <iconify-icon icon="solar:user-bold-duotone"></iconify-icon>
                                                    </span>
                                                </div>
                                                <div>
                                                    <span class="text-muted fs-12">Name</span> <br />
                                                    <h5 class="fs-14 mt-1">{{ $order->customer->first_name }} {{ $order->customer->second_name }}</h5>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="text-muted fs-12">Mobile</span> <br />
                                            <h5 class="fs-14 mt-1 fw-normal">{{ $order->customer->mobile }}</h5>
                                        </td>
                                        <td>
                                            <span class="text-muted fs-12">Latest order</span>
                                            <h5 class="fs-14 mt-1 fw-normal"><i class="ri-circle-fill fs-12 text-{{ $statusVariant[$order->status] }}"></i> {{ ucfirst(str_replace('_', ' ', $order->status)) }}</h5>
                                        </td>
                                        <td style="width: 30px;">
                                            <a href="{{ route('store.prescriptions.show', $order) }}" class="text-muted p-0">
                                                <i class="ri-eye-line"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-center text-muted py-4">No customers yet.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="text-muted text-center text-sm-start">
                        Showing <span class="fw-semibold">{{ $recentCustomerOrders->count() }}</span> recent customer(s)
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xxl-4">
            <div class="card">
                <div class="card-header d-flex flex-wrap align-items-center gap-2 border-bottom border-dashed">
                    <h4 class="header-title me-auto">Order Status Mix</h4>
                </div>
                <div class="card-body">
                    @if ($totalOrderCount > 0)
                        <div dir="ltr">
                            <div id="store-status-mix-chart" class="apex-charts" data-colors="#0d6efd,#0acf97,#f9bc0b,#39afd1,#fa5c7c"></div>
                        </div>
                    @else
                        <p class="text-muted text-center py-4 mb-0">Claim your first prescription to see a status breakdown here.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title mb-3">Quick Links</h4>
                    <div class="d-flex flex-wrap gap-2">
                        <a href="{{ route('store.prescriptions.index') }}" class="btn btn-soft-primary">
                            <i class="ri-file-list-3-line align-middle me-1"></i> Prescriptions
                        </a>
                        <a href="{{ route('store.settlements.index') }}" class="btn btn-soft-primary">
                            <i class="ri-hand-coin-line align-middle me-1"></i> COD Settlements
                        </a>
                        <a href="{{ route('store.profile.edit') }}" class="btn btn-soft-primary">
                            <i class="ri-store-2-line align-middle me-1"></i> Edit Store Profile
                        </a>
                        <a href="{{ route('store.captains.index') }}" class="btn btn-soft-primary">
                            <i class="ri-e-bike-2-line align-middle me-1"></i> Manage Captains
                        </a>
                    </div>

                    <hr class="my-3">

                    <p class="text-muted font-13 mb-0">
                        Orders and Stock modules aren't built yet — they'll show up here once those parts of the
                        platform ship (see the <a href="{{ route('roadmap') }}">roadmap</a>).
                    </p>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            (function () {
                // Own element IDs, deliberately not #statistics-chart /
                // #revenue-chart / #data-visits-chart — those are already
                // wired up with hardcoded demo data by the theme's global
                // assets/js/pages/dashboard.js (loaded on every Store page),
                // so reusing them would fight that script for the same div.
                var trendEl = document.querySelector('#store-claim-trend-chart');
                if (trendEl) {
                    new ApexCharts(trendEl, {
                        series: [{ name: 'Orders claimed', data: @json($claimTrend['values']) }],
                        chart: { height: 260, type: 'area', toolbar: { show: false }, zoom: { enabled: false } },
                        stroke: { width: 2, curve: 'smooth' },
                        dataLabels: { enabled: false },
                        xaxis: { categories: @json($claimTrend['labels']) },
                        yaxis: { labels: { formatter: function (v) { return Math.round(v); } } },
                        colors: (trendEl.dataset.colors || '').split(',').filter(Boolean),
                    }).render();
                }

                var statusEl = document.querySelector('#store-status-mix-chart');
                if (statusEl) {
                    new ApexCharts(statusEl, {
                        chart: { height: 300, type: 'donut' },
                        series: @json(array_values($statusBreakdown)),
                        labels: @json(array_keys($statusBreakdown)),
                        legend: { show: true, position: 'bottom', horizontalAlign: 'center', fontSize: '13px' },
                        colors: (statusEl.dataset.colors || '').split(',').filter(Boolean),
                        dataLabels: { enabled: true, formatter: function (val, opts) { return opts.w.config.series[opts.seriesIndex]; } },
                        responsive: [{ breakpoint: 600, options: { chart: { height: 240 }, legend: { show: false } } }],
                    }).render();
                }
            })();
        </script>
    @endpush

</x-layouts.store-layout>
