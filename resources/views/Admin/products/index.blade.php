<x-layouts.admin-layout title="Products">

    @push('styles')
        <link href="assets/vendor/datatables.net-bs5/css/dataTables.bootstrap5.min.css" rel="stylesheet" type="text/css" />
    @endpush

    @if (session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif
    <div id="ajax-status" class="alert alert-success d-none"></div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
                        <div>
                            <h4 class="header-title mb-1">Products</h4>
                            <p class="text-muted font-14 mb-0">{{ number_format($productCount) }} product(s) in the master catalog. Click Rx / Active to toggle either flag.</p>
                        </div>
                        <a href="{{ route('admin.products.import') }}" class="btn btn-primary">
                            <i class="ri-upload-2-line align-middle me-1"></i> Import from CSV/Excel
                        </a>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-auto">
                            <select id="filter-status" class="form-select form-select-sm">
                                <option value="">All Status</option>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                        <div class="col-auto">
                            <select id="filter-rx" class="form-select form-select-sm">
                                <option value="">All (Rx + OTC)</option>
                                <option value="rx">Rx only</option>
                                <option value="otc">OTC only</option>
                            </select>
                        </div>
                    </div>

                    <table id="products-datatable" class="table table-striped w-100 nowrap">
                        <thead>
                            <tr>
                                <th style="width:40px;">#</th>
                                <th></th>
                                <th>Code</th>
                                <th>Name</th>
                                <th>Item ID</th>
                                <th>Manufacturer</th>
                                <th>MRP</th>
                                <th>Price</th>
                                <th>Rx</th>
                                <th>Status</th>
                                <th style="width:70px;">Actions</th>
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
                const table = $('#products-datatable').DataTable({
                    processing: true,
                    serverSide: true,
                    scrollX: true,
                    ajax: {
                        url: '{{ route('admin.products.index') }}',
                        data: function (d) {
                            d.status = $('#filter-status').val();
                            d.rx = $('#filter-rx').val();
                        },
                    },
                    columns: [
                        { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                        { data: 'thumbnail', name: 'thumbnail', orderable: false, searchable: false },
                        { data: 'code', name: 'code' },
                        { data: 'name', name: 'name' },
                        { data: 'item_id', name: 'item_id' },
                        { data: 'manufacturer', name: 'manufacturer' },
                        { data: 'mrp', name: 'mrp' },
                        { data: 'price', name: 'price' },
                        { data: 'requires_prescription', name: 'requires_prescription', orderable: false, searchable: false },
                        { data: 'is_active', name: 'is_active', orderable: false, searchable: false },
                        { data: 'actions', name: 'actions', orderable: false, searchable: false },
                    ],
                });

                // Filter dropdowns just re-trigger the same server-side
                // ajax call with their value attached (see ajax.data
                // above) — draw(), not a full page reload, so paging/
                // search stay in sync with the filter.
                $('#filter-status, #filter-rx').on('change', function () {
                    table.draw();
                });

                function flashStatus(message) {
                    const $el = $('#ajax-status');
                    $el.text(message).removeClass('d-none');
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                    setTimeout(function () { $el.addClass('d-none'); }, 4000);
                }

                // Rx / Active toggles now go over AJAX instead of a form
                // POST + full page reload — table.ajax.reload(null, false)
                // re-fetches this row's data without resetting the
                // DataTable's current page/search/filter state.
                $('#products-datatable').on('click', '.toggle-rx-btn, .toggle-active-btn', function () {
                    const $btn = $(this);

                    $.post($btn.data('url'), { _token: '{{ csrf_token() }}' })
                        .done(function (res) {
                            flashStatus(res.status);
                            table.ajax.reload(null, false);
                        })
                        .fail(function () {
                            alert('Could not update — please try again.');
                        });
                });
            });
        </script>
    @endpush

</x-layouts.admin-layout>
