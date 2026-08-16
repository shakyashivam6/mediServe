<x-layouts.store-layout title="My Captains">

    @push('styles')
        <link href="assets/vendor/datatables.net-bs5/css/dataTables.bootstrap5.min.css" rel="stylesheet" type="text/css" />
    @endpush

    @if (session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    @unless ($storeApproved)
        <div class="alert alert-warning">Your store must be approved by Admin before you can add Captains.</div>
    @endunless

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div>
                            <h4 class="header-title mb-1">My Captains</h4>
                            <p class="text-muted font-14 mb-0">Delivery staff linked to your store.</p>
                        </div>
                        @if ($storeApproved)
                            <a href="{{ route('store.captains.create') }}" class="btn btn-primary">
                                <i class="ri-add-line align-middle me-1"></i> Add Captain
                            </a>
                        @endif
                    </div>

                    <table id="captains-datatable" class="table table-striped w-100 nowrap">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Vehicle</th>
                                <th>Status</th>
                                <th style="width: 100px;">Actions</th>
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
                $('#captains-datatable').DataTable({
                    processing: true,
                    serverSide: true,
                    scrollX: true,
                    ajax: '{{ route('store.captains.index') }}',
                    columns: [
                        { data: 'name', name: 'first_name' },
                        { data: 'vehicle_type', name: 'vehicle_type' },
                        { data: 'status', name: 'isActive' },
                        { data: 'actions', name: 'actions', orderable: false, searchable: false },
                    ],
                });
            });
        </script>
    @endpush

</x-layouts.store-layout>
