<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;

class RoleController extends Controller
{
    /**
     * List Admin roles. Renders the page on a normal GET; on the DataTables
     * ajax GET it returns the server-side JSON payload instead.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return DataTables::of(Role::withCount(['permissions', 'users']))
                ->addColumn('actions', function (Role $role) {
                    $editUrl = route('admin.roles.edit', $role);

                    $deleteForm = '';
                    if ($role->name !== 'Super Admin') {
                        $deleteForm = '<form method="POST" action="'.route('admin.roles.destroy', $role).'" class="d-inline" onsubmit="return confirm(\'Delete this role?\');">'
                            .csrf_field().method_field('DELETE')
                            .'<button type="submit" class="btn btn-soft-danger btn-sm"><i class="ri-delete-bin-line"></i></button>'
                            .'</form>';
                    }

                    return '<a href="'.$editUrl.'" class="btn btn-soft-primary btn-sm me-1"><i class="ri-pencil-line"></i></a>'.$deleteForm;
                })
                ->rawColumns(['actions'])
                ->make(true);
        }

        return view('Admin.roles.index');
    }

    /**
     * Show the form for creating a new role.
     */
    public function create()
    {
        return view('Admin.roles.create', [
            'permissionGroups' => $this->permissionGroups(),
        ]);
    }

    /**
     * Store a newly created role.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100', Rule::unique('roles', 'name')],
            'permissions' => ['array'],
            'permissions.*' => ['string', 'exists:permissions,name'],
        ]);

        $role = Role::create(['name' => $data['name']]);
        $role->syncPermissions($data['permissions'] ?? []);

        return redirect()->route('admin.roles.index')->with('status', "Role \"{$role->name}\" created.");
    }

    /**
     * Show the form for editing a role's permissions and assigned users.
     */
    public function edit(Role $role)
    {
        return view('Admin.roles.edit', [
            'role' => $role,
            'permissionGroups' => $this->permissionGroups(),
            'rolePermissions' => $role->permissions->pluck('name')->all(),
            'eligibleUsers' => $this->eligibleUsers(),
            'roleUserIds' => User::role($role->name)->pluck('id')->all(),
        ]);
    }

    /**
     * Update the role's name, permissions, and which admin users hold it.
     */
    public function update(Request $request, Role $role)
    {
        $isSuperAdmin = $role->name === 'Super Admin';

        $nameRules = ['required', 'string', 'max:100', Rule::unique('roles', 'name')->ignore($role->id)];
        if ($isSuperAdmin) {
            // Super Admin is the platform's break-glass role — keep its name fixed.
            $nameRules[] = Rule::in(['Super Admin']);
        }

        $data = $request->validate([
            'name' => $nameRules,
            'permissions' => ['array'],
            'permissions.*' => ['string', 'exists:permissions,name'],
            'users' => ['array'],
            'users.*' => ['integer', 'exists:users,id'],
        ]);

        $role->update(['name' => $data['name']]);

        // Super Admin always keeps every permission — it also bypasses every
        // check via Gate::before, but this keeps the UI honest about it.
        $role->syncPermissions($isSuperAdmin ? Permission::all() : ($data['permissions'] ?? []));

        $selectedUserIds = $data['users'] ?? [];
        foreach ($this->eligibleUsers() as $user) {
            in_array($user->id, $selectedUserIds) ? $user->assignRole($role) : $user->removeRole($role);
        }

        return redirect()->route('admin.roles.index')->with('status', "Role \"{$role->name}\" updated.");
    }

    /**
     * Remove a role. The Super Admin role is protected from deletion.
     */
    public function destroy(Role $role)
    {
        abort_if($role->name === 'Super Admin', 403, 'The Super Admin role cannot be deleted.');

        $role->delete();

        return redirect()->route('admin.roles.index')->with('status', 'Role deleted.');
    }

    /**
     * All permissions grouped by their module prefix (before the first
     * dot), e.g. "stores.view" and "stores.manage" both land under "stores".
     * Drives the checkbox grid on the create/edit forms.
     */
    protected function permissionGroups()
    {
        return Permission::all()->groupBy(fn (Permission $permission) => Str::before($permission->name, '.'));
    }

    /**
     * Account types a role can be assigned to: Admin, Customer, Captain.
     * Store is deliberately excluded — a Store's own staff permissions are
     * a separate concern from these platform-wide Admin roles.
     */
    protected function eligibleUsers()
    {
        return User::whereIn('role', ['admin', 'customer', 'captain'])->orderBy('first_name')->get();
    }
}
