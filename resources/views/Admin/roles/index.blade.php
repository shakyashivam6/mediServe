<x-layouts.admin-layout title="Roles & Permissions">

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
                            <h4 class="header-title mb-1">Roles &amp; Permissions</h4>
                            <p class="text-muted font-14 mb-0">Admin roles and the permissions each one holds. The sidebar menu shows only what a role is permitted to see.</p>
                        </div>
                        <a href="{{ route('admin.roles.create') }}" class="btn btn-primary">
                            <i class="ri-add-line align-middle me-1"></i> Add Role
                        </a>
                    </div>

                    <table id="roles-datatable" class="table table-striped w-100 nowrap">
                        <thead>
                            <tr>
                                <th>Role</th>
                                <th>Permissions</th>
                                <th>Users</th>
                                <th style="width: 110px;">Actions</th>
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
                $('#roles-datatable').DataTable({
                    processing: true,
                    serverSide: true,
                    scrollX: true,
                    ajax: '{{ route('admin.roles.index') }}',
                    columns: [
                        { data: 'name', name: 'name' },
                        { data: 'permissions_count', name: 'permissions_count', searchable: false },
                        { data: 'users_count', name: 'users_count', searchable: false },
                        { data: 'actions', name: 'actions', orderable: false, searchable: false },
                    ],
                });
            });
        </script>
    @endpush

</x-layouts.admin-layout>
