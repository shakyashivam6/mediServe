<x-layouts.admin-layout title="Stores">

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
                            <h4 class="header-title mb-1">Stores</h4>
                            <p class="text-muted font-14 mb-0">Pharmacies registered on the platform. New stores need approval before their login is active.</p>
                        </div>
                        @can('stores.manage')
                            <a href="{{ route('admin.stores.create') }}" class="btn btn-primary">
                                <i class="ri-add-line align-middle me-1"></i> Add Store
                            </a>
                        @endcan
                    </div>

                    <table id="stores-datatable" class="table table-striped w-100 nowrap">
                        <thead>
                            <tr>
                                <th>Shop Name</th>
                                <th>Owner</th>
                                <th>Mobile</th>
                                <th>Status</th>
                                <th style="width: 130px;">Actions</th>
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
                $('#stores-datatable').DataTable({
                    processing: true,
                    serverSide: true,
                    scrollX: true,
                    ajax: '{{ route('admin.stores.index') }}',
                    columns: [
                        { data: 'shop_name', name: 'shop_name' },
                        { data: 'owner', name: 'user.first_name', orderable: false },
                        { data: 'mobile', name: 'user.mobile', orderable: false },
                        { data: 'status', name: 'status' },
                        { data: 'actions', name: 'actions', orderable: false, searchable: false },
                    ],
                });
            });
        </script>
    @endpush

</x-layouts.admin-layout>
