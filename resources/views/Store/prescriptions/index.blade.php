<x-layouts.store-layout title="Prescriptions">

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
                            <h4 class="header-title mb-1">Prescriptions</h4>
                            <p class="text-muted font-14 mb-0">
                                Shared queue — unclaimed uploads are visible to every store; open one to claim and review it.
                            </p>
                        </div>
                    </div>

                    <table id="prescriptions-datatable" class="table table-striped w-100 nowrap">
                        <thead>
                            <tr>
                                <th>Prescription ID</th>
                                <th>Customer</th>
                                <th>Mobile</th>
                                <th>Uploaded</th>
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
                $('#prescriptions-datatable').DataTable({
                    processing: true,
                    serverSide: true,
                    scrollX: true,
                    order: [[3, 'desc']],
                    ajax: '{{ route('store.prescriptions.index') }}',
                    columns: [
                        { data: 'prescription_number', name: 'prescription_number' },
                        { data: 'customer', name: 'customer.first_name', orderable: false },
                        { data: 'mobile', name: 'customer.mobile', orderable: false },
                        { data: 'uploaded', name: 'created_at' },
                        { data: 'status', name: 'status' },
                        { data: 'actions', name: 'actions', orderable: false, searchable: false },
                    ],
                });
            });
        </script>
    @endpush

</x-layouts.store-layout>
