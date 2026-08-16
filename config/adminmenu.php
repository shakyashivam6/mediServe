<?php

/**
 * Sidebar menus — structure lives in code, visibility is data-driven. This
 * one file holds every role's menu, keyed by account type; the Store/Captain
 * panels intentionally reuse this file instead of a separate config so all
 * menu structure stays in one place (see
 * resources/views/common/sidenav.blade.php, which picks the 'admin'/'store'/
 * 'captain' array based on auth()->user()->role).
 *
 * Each leaf item carries a `permission` slug (or null to show for every
 * logged-in user of that menu, e.g. Dashboard). A group with `children`
 * hides itself automatically once none of its children pass their
 * permission check — no per-role menu logic needed beyond the top-level
 * admin/store/captain split.
 *
 * Adding a new Admin module: create its routes/controller/views, seed its
 * permission slug (see database/seeders/RolePermissionSeeder.php), then add
 * one entry under 'admin' below. Store/Captain menus have no permission
 * system behind them (both account types are deliberately excluded from
 * Spatie roles — see project memory) — every 'store'/'captain' item is just
 * gated by the `account_role:store`/`account_role:captain` route middleware
 * instead, so `permission` stays null throughout those arrays.
 */
return [

    'admin' => [
        [
            'label' => 'Dashboard',
            'icon' => 'ri-dashboard-3-line',
            'route' => 'dashboard',
            'permission' => null,
        ],

        [
            'label' => 'Access Control',
            'icon' => 'ri-shield-user-line',
            'children' => [
                ['label' => 'Roles & Permissions', 'route' => 'admin.roles.index', 'permission' => 'roles.manage'],
            ],
        ],

        [
            'label' => 'Stores',
            'icon' => 'ri-store-2-line',
            'route' => 'admin.stores.index',
            'permission' => 'stores.view',
        ],
        [
            'label' => 'Captains',
            'icon' => 'ri-e-bike-2-line',
            'route' => 'admin.captains.index',
            'permission' => 'captains.manage',
        ],

        [
            'label' => 'Products',
            'icon' => 'ri-capsule-line',
            'route' => 'admin.products.index',
            'permission' => 'catalog.manage',
        ],

        // Planned modules — uncomment once each one's routes/controller/views
        // exist. Permission slugs are already seeded (RolePermissionSeeder).
        //
        // [
        //     'label' => 'Orders',
        //     'icon' => 'ri-file-list-3-line',
        //     'route' => 'admin.orders.index',
        //     'permission' => 'orders.view',
        // ],
        // [
        //     'label' => 'Prescriptions',
        //     'icon' => 'ri-file-text-line',
        //     'route' => 'admin.prescriptions.index',
        //     'permission' => 'prescriptions.review',
        // ],
        // [
        //     'label' => 'Coupons & Offers',
        //     'icon' => 'ri-coupon-3-line',
        //     'route' => 'admin.coupons.index',
        //     'permission' => 'coupons.manage',
        // ],
        // [
        //     'label' => 'Reports',
        //     'icon' => 'ri-bar-chart-2-line',
        //     'route' => 'admin.reports.index',
        //     'permission' => 'reports.view',
        // ],

        [
            'label' => 'Settings',
            'icon' => 'ri-settings-3-line',
            'route' => 'admin.settings.edit',
            'permission' => 'settings.manage',
        ],
    ],

    'store' => [
        [
            'label' => 'Dashboard',
            'icon' => 'ri-dashboard-3-line',
            'route' => 'store.dashboard',
            'permission' => null,
        ],
        [
            'label' => 'My Store Profile',
            'icon' => 'ri-store-2-line',
            'route' => 'store.profile.edit',
            'permission' => null,
        ],
        [
            'label' => 'My Captains',
            'icon' => 'ri-e-bike-2-line',
            'route' => 'store.captains.index',
            'permission' => null,
        ],
        [
            'label' => 'Prescriptions',
            'icon' => 'ri-file-text-line',
            'route' => 'store.prescriptions.index',
            'permission' => null,
        ],
        [
            'label' => 'COD Settlements',
            'icon' => 'ri-hand-coin-line',
            'route' => 'store.settlements.index',
            'permission' => null,
        ],
        [
            'label' => 'Notifications',
            'icon' => 'ri-notification-3-line',
            'route' => 'store.notifications.index',
            'permission' => null,
        ],

        // Planned — once each module exists, a Store only ever sees its own
        // slice of it (own stock, own orders), unlike the Admin versions
        // above which see everything.
        //
        // [
        //     'label' => 'Stock',
        //     'icon' => 'ri-archive-2-line',
        //     'route' => 'store.stock.index',
        //     'permission' => null,
        // ],
        // [
        //     'label' => 'Orders',
        //     'icon' => 'ri-file-list-3-line',
        //     'route' => 'store.orders.index',
        //     'permission' => null,
        // ],
        // [
        //     'label' => 'Sales Reports',
        //     'icon' => 'ri-bar-chart-2-line',
        //     'route' => 'store.reports.index',
        //     'permission' => null,
        // ],
    ],

    'captain' => [
        [
            'label' => 'Dashboard',
            'icon' => 'ri-dashboard-3-line',
            'route' => 'captain.dashboard',
            'permission' => null,
        ],
        [
            'label' => 'My Collections',
            'icon' => 'ri-hand-coin-line',
            'route' => 'captain.collections.index',
            'permission' => null,
        ],
        [
            'label' => 'Notifications',
            'icon' => 'ri-notification-3-line',
            'route' => 'captain.notifications.index',
            'permission' => null,
        ],
    ],

];
