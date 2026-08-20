<div class="sidenav-menu">

    <!-- Brand Logo -->
    <a href="index.html" class="logo">
        <span class="logo-light">
            <span class="logo-lg"><img src="{{ $siteLogo }}" alt="logo" style="width: 10rem; height: 4rem;"></span>
            <span class="logo-sm"><img src="{{ $siteLogo }}" alt="small logo"></span>
        </span>

        <span class="logo-dark">
            <span class="logo-lg"><img src="{{ $siteLogo }}" alt="dark logo" style="width: 10rem; height: 4rem;"></span>
            <span class="logo-sm"><img src="{{ $siteLogo }}" alt="small logo"></span>
        </span>
    </a>

    <!-- Sidebar Hover Menu Toggle Button -->
    <button class="button-sm-hover">
        <i class="ri-circle-line align-middle"></i>
    </button>

    <!-- Full Sidebar Menu Close Button -->
    <button class="button-close-fullsidebar">
        <i class="ri-close-line align-middle"></i>
    </button>

    <div data-simplebar>

        <!-- User -->
        <div class="sidenav-user">
            <div class="dropdown-center">
                <a class="topbar-link dropdown-toggle text-reset drop-arrow-none px-2 d-flex align-items-center justify-content-center"
                    data-bs-toggle="dropdown" data-bs-offset="0,19" type="button" aria-haspopup="false"
                    aria-expanded="false">
                    <img src="assets/images/users/avatar-1.jpg" width="42" class="rounded-circle me-2 d-flex"
                        alt="user-image">
                    <span class="d-flex flex-column gap-1 sidebar-user-name">
                        <h4 class="my-0 fw-bold fs-15">{{ auth()->user()->first_name }}
                            {{ auth()->user()->second_name }}</h4>
                        <h6 class="my-0">{{ auth()->user()->getRoleNames()->first() ?? ucfirst(auth()->user()->role) }}
                        </h6>
                    </span>
                    <i class="ri-arrow-down-s-line d-block sidebar-user-arrow align-middle ms-2"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-end">
                    <!-- item-->
                    <div class="dropdown-header noti-title">
                        <h6 class="text-overflow m-0">Welcome !</h6>
                    </div>

                    <!-- item-->
                    <a href="javascript:void(0);" class="dropdown-item">
                        <i class="ri-account-circle-line me-1 fs-16 align-middle"></i>
                        <span class="align-middle">My Account</span>
                    </a>

                    <!-- item-->
                    <a href="javascript:void(0);" class="dropdown-item">
                        <i class="ri-wallet-3-line me-1 fs-16 align-middle"></i>
                        <span class="align-middle">Wallet : <span class="fw-semibold">$89.25k</span></span>
                    </a>

                    <!-- item-->
                    <a href="javascript:void(0);" class="dropdown-item">
                        <i class="ri-settings-2-line me-1 fs-16 align-middle"></i>
                        <span class="align-middle">Settings</span>
                    </a>

                    <!-- item-->
                    <a href="javascript:void(0);" class="dropdown-item">
                        <i class="ri-question-line me-1 fs-16 align-middle"></i>
                        <span class="align-middle">Support</span>
                    </a>

                    <div class="dropdown-divider"></div>

                    <!-- item-->
                    <a href="javascript:void(0);" class="dropdown-item">
                        <i class="ri-lock-line me-1 fs-16 align-middle"></i>
                        <span class="align-middle">Lock Screen</span>
                    </a>

                    <!-- item-->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item active fw-semibold text-danger">
                            <i class="ri-logout-box-line me-1 fs-16 align-middle"></i>
                            <span class="align-middle">Sign Out</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{--
        Sidenav Menu — rendered from config/adminmenu.php, not hardcoded.
        That one file holds the Admin, Store and Captain menus; which array
        renders here depends purely on the logged-in account's role, which
        is what keeps the menus separate — a Store/Captain account never
        sees Admin items and vice versa, regardless of any permission it
        holds. Each leaf item carries a permission slug; a null permission
        means "visible to any logged-in user of that menu" (e.g. Dashboard).
        A group with children hides itself once none of its children pass
        their check — see config/adminmenu.php for how to add a new module.
        --}}
        @php
            $sidenavMenu = config('adminmenu.' . (in_array(auth()->user()->role, ['store', 'captain']) ? auth()->user()->role : 'admin'), []);

            // Per-item nav badge counts, keyed by label — driven by actual
            // prescription/payment state rather than raw unread
            // notifications, so the number always matches what's sitting in
            // that item's own list.
            //
            // Store: a prescription counts against "Prescriptions" from the
            // moment it's visible (unclaimed, or claimed by this Store)
            // until it's delivered — that's the whole pre-delivery workflow
            // (claim, call, estimate, confirm, dispatch). The moment it's
            // delivered it drops off Prescriptions and, if it was COD,
            // picks up under "COD Settlements" instead — staying there
            // until the Store marks the cash settled. `rejected` is
            // excluded throughout: nothing more to do on either screen.
            $badgeCounts = match (auth()->user()->role) {
                'store' => [
                    'Prescriptions' => \App\Models\Prescription::query()
                        ->where(fn ($q) => $q->whereNull('store_id')->orWhere('store_id', auth()->id()))
                        ->whereNotIn('status', ['delivered', 'rejected'])
                        ->count(),
                    'COD Settlements' => \App\Models\Prescription::query()
                        ->where('store_id', auth()->id())
                        ->where('status', 'delivered')
                        ->where('payment_method', 'cod')
                        ->where('payment_status', '!=', 'settled')
                        ->count(),
                ],
                // Captain has no Prescriptions/Settlements tab of its own —
                // its Notifications item keeps the plain unread count.
                'captain' => ['Notifications' => auth()->user()->unreadNotifications()->count()],
                default => [],
            };
        @endphp
        <ul class="side-nav">
            <li class="side-nav-title">Navigation</li>

            @foreach ($sidenavMenu as $item)
                @if (isset($item['children']))
                    @php
                        $visibleChildren = collect($item['children'])->filter(
                            fn($child) => is_null($child['permission']) || auth()->user()->can($child['permission'])
                        );
                    @endphp
                    @continue($visibleChildren->isEmpty())
                    @php $menuId = 'sidebar-' . \Illuminate\Support\Str::slug($item['label']); @endphp
                    <li class="side-nav-item">
                        <a data-bs-toggle="collapse" href="#{{ $menuId }}" aria-expanded="false" aria-controls="{{ $menuId }}"
                            class="side-nav-link">
                            <span class="menu-icon"><i class="{{ $item['icon'] }}"></i></span>
                            <span class="menu-text"> {{ $item['label'] }} </span>
                            <span class="menu-arrow"></span>
                        </a>
                        <div class="collapse" id="{{ $menuId }}">
                            <ul class="sub-menu">
                                @foreach ($visibleChildren as $child)
                                    <li class="side-nav-item">
                                        <a href="{{ route($child['route']) }}" class="side-nav-link">
                                            <span class="menu-text">{{ $child['label'] }}</span>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </li>
                @elseif (is_null($item['permission']) || auth()->user()->can($item['permission']))
                    <li class="side-nav-item">
                        <a href="{{ route($item['route']) }}" class="side-nav-link">
                            <span class="menu-icon"><i class="{{ $item['icon'] }}"></i></span>
                            <span class="menu-text"> {{ $item['label'] }} </span>
                            @if (($badgeCounts[$item['label']] ?? 0) > 0)
                                <span class="badge bg-danger rounded-pill">{{ $badgeCounts[$item['label']] }}</span>
                            @endif
                        </a>
                    </li>
                @endif
            @endforeach
        </ul>

        <div class="clearfix"></div>
    </div>
</div>