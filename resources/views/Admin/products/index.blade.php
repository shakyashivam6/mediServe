<x-layouts.admin-layout title="Products">

    @push('styles')
        <link href="assets/vendor/datatables.net-bs5/css/dataTables.bootstrap5.min.css" rel="stylesheet" type="text/css" />
    @endpush

    @if (session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div>
                            <h4 class="header-title mb-1">Products</h4>
                            <p class="text-muted font-14 mb-0">{{ number_format($productCount) }} product(s) in the master catalog. Click Rx / Active to toggle either flag.</p>
                        </div>
                        <a href="{{ route('admin.products.import') }}" class="btn btn-primary">
                            <i class="ri-upload-2-line align-middle me-1"></i> Import from CSV/Excel
                        </a>
                    </div>

                    <table id="products-datatable" class="table table-striped w-100 nowrap">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Code</th>
                                <th>Name</th>
                                <th>Item ID</th>
                                <th>Manufacturer</th>
                                <th>MRP</th>
                                <th>Price</th>
                                <th>Rx</th>
                                <th>Status</th>
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
                $('#products-datatable').DataTable({
                    processing: true,
                    serverSide: true,
                    scrollX: true,
                    ajax: '{{ route('admin.products.index') }}',
                    columns: [
                        { data: 'thumbnail', name: 'thumbnail', orderable: false, searchable: false },
                        { data: 'code', name: 'code' },
                        { data: 'name', name: 'name' },
                        { data: 'item_id', name: 'item_id' },
                        { data: 'manufacturer', name: 'manufacturer' },
                        { data: 'mrp', name: 'mrp' },
                        { data: 'price', name: 'price' },
                        { data: 'requires_prescription', name: 'requires_prescription', orderable: false, searchable: false },
                        { data: 'is_active', name: 'is_active', orderable: false, searchable: false },
                    ],
                });
            });
        </script>
    @endpush

</x-layouts.admin-layout>
