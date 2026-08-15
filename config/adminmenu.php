<?php

/**
 * Admin sidebar menu — structure lives in code, visibility is data-driven.
 *
 * Each leaf item carries a `permission` slug (or null to show for every
 * logged-in Admin, e.g. Dashboard). resources/views/common/sidenav.blade.php
 * loops over this array and wraps each item in @can($item['permission']).
 * A group with `children` hides itself automatically once none of its
 * children pass their permission check — no per-role menu logic needed.
 *
 * Adding a new module: create its routes/controller/views, seed its
 * permission slug (see database/seeders/RolePermissionSeeder.php), then
 * add one entry below. Nothing else has to change for it to show up for
 * the right roles.
 */
return [
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

    // Planned modules — uncomment once each one's routes/controller/views
    // exist. Permission slugs are already seeded (RolePermissionSeeder).
    //
    // [
    //     'label' => 'Catalog',
    //     'icon' => 'ri-capsule-line',
    //     'route' => 'admin.catalog.index',
    //     'permission' => 'catalog.manage',
    // ],
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
];
