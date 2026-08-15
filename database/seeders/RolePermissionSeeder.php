<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    /**
     * Permission slugs, grouped by admin module (module.action). Most of
     * these guard modules that are still on the roadmap and not built yet
     * (stores, catalog, orders, ...) — seeding them now means the Roles &
     * Permissions screen already has real content to assign, and each
     * module just needs to reference its slug once it ships.
     */
    protected array $permissions = [
        'roles' => ['roles.manage'],
        'users' => ['users.manage'],
        'stores' => ['stores.view', 'stores.approve', 'stores.manage'],
        'captains' => ['captains.manage'],
        'catalog' => ['catalog.manage'],
        'orders' => ['orders.view', 'orders.manage'],
        'prescriptions' => ['prescriptions.review'],
        'coupons' => ['coupons.manage'],
        'content' => ['content.manage'],
        'reports' => ['reports.view'],
        'settings' => ['settings.manage'],
    ];

    /**
     * Default Admin roles and the permission slugs each one starts with.
     * Super Admin gets every permission explicitly (on top of the
     * Gate::before bypass in AppServiceProvider) so it shows fully-checked
     * in the UI rather than looking empty.
     */
    protected array $roles = [
        'Super Admin' => '*',
        'Store Manager' => ['stores.view', 'stores.approve', 'stores.manage', 'captains.manage'],
        'Catalog Manager' => ['catalog.manage', 'coupons.manage'],
        'Support Admin' => ['orders.view', 'prescriptions.review', 'content.manage'],
        'Finance Admin' => ['orders.view', 'reports.view'],
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $allSlugs = collect($this->permissions)->flatten();

        $allSlugs->each(fn (string $slug) => Permission::findOrCreate($slug));

        // Spatie caches the full permission list forever; anything that
        // booted the app earlier in this process (e.g. an artisan command
        // run before this seeder) may have cached it as empty. Without this,
        // syncPermissions() below can't find the permissions we just made.
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        foreach ($this->roles as $roleName => $rolePermissions) {
            $role = Role::findOrCreate($roleName);

            $role->syncPermissions($rolePermissions === '*' ? $allSlugs : $rolePermissions);
        }
    }
}
