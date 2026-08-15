<x-layouts.admin-layout title="Add Role">

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title mb-3">Add Role</h4>

                    <form method="POST" action="{{ route('admin.roles.store') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="name" class="form-label">Role name</label>
                            <input type="text" name="name" id="name"
                                class="form-control @error('name') is-invalid @enderror"
                                value="{{ old('name') }}" placeholder="e.g. Store Manager" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <label class="form-label">Permissions</label>
                        <div class="row">
                            @foreach ($permissionGroups as $group => $permissions)
                                <div class="col-md-4 mb-3">
                                    <div class="card border">
                                        <div class="card-body">
                                            <h5 class="fs-14 text-uppercase text-muted mb-2">{{ $group }}</h5>
                                            @foreach ($permissions as $permission)
                                                <div class="form-check mb-1">
                                                    <input type="checkbox" class="form-check-input" name="permissions[]"
                                                        id="perm-{{ $permission->id }}" value="{{ $permission->name }}"
                                                        @checked(in_array($permission->name, old('permissions', [])))>
                                                    <label class="form-check-label" for="perm-{{ $permission->id }}">{{ $permission->name }}</label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <button type="submit" class="btn btn-primary mt-2">Create Role</button>
                        <a href="{{ route('admin.roles.index') }}" class="btn btn-light mt-2">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>

</x-layouts.admin-layout>
