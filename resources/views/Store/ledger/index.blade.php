<x-layouts.store-layout title="Store Ledger">

    @push('styles')
        <link href="assets/vendor/datatables.net-bs5/css/dataTables.bootstrap5.min.css" rel="stylesheet" type="text/css" />
    @endpush

    <div class="row row-cols-1 row-cols-md-2 row-cols-xl-4 g-3 mb-1">
        <div class="col">
            <div class="card mb-0">
                <div class="card-body">
                    <p class="text-muted mb-1">Today's Sales</p>
                    <h3 class="mb-0">₹{{ number_format((float) $todaySales, 2) }}</h3>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card mb-0">
                <div class="card-body">
                    <p class="text-muted mb-1">This Month's Sales</p>
                    <h3 class="mb-0">₹{{ number_format((float) $monthSales, 2) }}</h3>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card mb-0">
                <div class="card-body">
                    <p class="text-muted mb-1">Total Received</p>
                    <h3 class="mb-0 text-success">₹{{ number_format((float) $totalReceived, 2) }}</h3>
                    <p class="text-muted font-12 mb-0">Prepaid + settled COD, all-time</p>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card mb-0">
                <div class="card-body">
                    <p class="text-muted mb-1">Pending Collection</p>
                    <h3 class="mb-0 text-warning">₹{{ number_format((float) $totalPending, 2) }}</h3>
                    <p class="text-muted font-12 mb-0">COD not yet settled, all-time</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
                        <div>
                            <h4 class="header-title mb-1">Daily Sales Summary</h4>
                            <p class="text-muted font-14 mb-0">Delivered orders, grouped by day, for the period below.</p>
                        </div>
                        <form method="GET" class="d-flex align-items-end gap-2 flex-wrap">
                            <div>
                                <label class="form-label font-12 mb-1">From</label>
                                <input type="date" name="from" class="form-control form-control-sm" value="{{ $from->toDateString() }}">
                            </div>
                            <div>
                                <label class="form-label font-12 mb-1">To</label>
                                <input type="date" name="to" class="form-control form-control-sm" value="{{ $to->toDateString() }}">
                            </div>
                            <button type="submit" class="btn btn-primary btn-sm"><i class="ri-filter-3-line align-middle"></i> Apply</button>
                        </form>
                    </div>

                    @if ($dailySummary->isEmpty())
                        <p class="text-muted mb-0">No delivered sales in this period.</p>
                    @else
                        <div class="table-responsive">
                            <table class="table table-sm mb-0">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Orders</th>
                                        <th class="text-end">Total Sales</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($dailySummary as $day)
                                        <tr>
                                            <td>{{ \Illuminate\Support\Carbon::parse($day->day)->format('d M Y') }}</td>
                                            <td>{{ $day->orders }}</td>
                                            <td class="text-end">₹{{ number_format((float) $day->total, 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr class="fw-bold">
                                        <td>Period Total</td>
                                        <td>{{ $periodCount }}</td>
                                        <td class="text-end">₹{{ number_format((float) $periodTotal, 2) }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title mb-1">Sales Ledger</h4>
                    <p class="text-muted font-14 mb-3">Every delivered order for the period above — one line per payment, accounting-style.</p>

                    <table id="ledger-datatable" class="table table-striped w-100 nowrap">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Order</th>
                                <th>Customer</th>
                                <th>Mode</th>
                                <th>Status</th>
                                <th>Amount (₹)</th>
                                <th style="width:60px;">Bill</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="assets/vendor/datatables.net/js/jquery.dataTables.min.js"></script>
        <script src="assets/vendor/datatables.net-bs5/js/dataTables.bootstrap5.min.js"></script>
        <script>
            $(document).ready(function () {
                $('#ledger-datatable').DataTable({
                    processing: true,
                    serverSide: true,
                    scrollX: true,
                    order: [[0, 'desc']],
                    ajax: {
                        url: '{{ route('store.ledger.index') }}',
                        data: function (d) {
                            d.from = $('input[name=from]').val();
                            d.to = $('input[name=to]').val();
                        },
                    },
                    columns: [
                        { data: 'date', name: 'delivered_at' },
                        { data: 'order', name: 'id' },
                        { data: 'customer', name: 'customer.first_name', orderable: false },
                        { data: 'payment_mode', name: 'payment_method' },
                        { data: 'payment_status', name: 'payment_status' },
                        { data: 'amount', name: 'total_amount' },
                        { data: 'actions', name: 'actions', orderable: false, searchable: false },
                    ],
                });
            });
        </script>
    @endpush

</x-layouts.store-layout>
