<x-layouts.admin-layout title="Edit Role">

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title mb-3">Edit Role</h4>

                    @if ($role->name === 'Super Admin')
                        <div class="alert alert-info">
                            Super Admin always holds every permission (it bypasses every check platform-wide) and its name can't change — the only thing to configure here is who holds it.
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin.roles.update', $role) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="name" class="form-label">Role name</label>
                            <input type="text" name="name" id="name"
                                class="form-control @error('name') is-invalid @enderror"
                                value="{{ old('name', $role->name) }}" @disabled($role->name === 'Super Admin') required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        @unless ($role->name === 'Super Admin')
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
                                                            @checked(in_array($permission->name, old('permissions', $rolePermissions)))>
                                                        <label class="form-check-label" for="perm-{{ $permission->id }}">{{ $permission->name }}</label>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endunless

                        <label class="form-label">Assigned to <span class="text-muted">(Admin, Customer &amp; Captain accounts)</span></label>
                        <div class="mb-3">
                            @forelse ($eligibleUsers as $user)
                                <div class="form-check mb-1">
                                    <input type="checkbox" class="form-check-input" name="users[]"
                                        id="user-{{ $user->id }}" value="{{ $user->id }}"
                                        @checked(in_array($user->id, old('users', $roleUserIds)))>
                                    <label class="form-check-label" for="user-{{ $user->id }}">
                                        {{ $user->first_name }} {{ $user->second_name }} ({{ $user->login_id }})
                                        <span class="badge bg-light text-dark">{{ ucfirst($user->role) }}</span>
                                    </label>
                                </div>
                            @empty
                                <p class="text-muted">No eligible users yet.</p>
                            @endforelse
                        </div>

                        <button type="submit" class="btn btn-primary mt-2">Save Changes</button>
                        <a href="{{ route('admin.roles.index') }}" class="btn btn-light mt-2">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>

</x-layouts.admin-layout>
