@props(['title' => 'Dashboard'])
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>{{ $title }} | {{ config('app.name') }} Store Panel</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="{{ config('app.name') }} Store Panel — manage your store profile, captains, prescription orders and COD settlements." name="description" />
    <meta content="Tejasweb Solutions" name="author" />
    <base href="{{ url('/') }}">

    <!-- App favicon -->
    <link rel="shortcut icon" href="assets/images/favicon.ico">

    <!-- Vendor css -->
    <link href="assets/css/vendor.min.css" rel="stylesheet" type="text/css" />

    <!-- App css -->
    <link href="assets/css/app.min.css" rel="stylesheet" type="text/css" id="app-style" />

    <!-- Icons css -->
    <link href="assets/css/icons.min.css" rel="stylesheet" type="text/css" />

    <!-- Theme Config Js -->
    <script src="assets/js/config.js"></script>

    {{-- Per-page extra CSS (e.g. DataTables) --}}
    @stack('styles')
</head>

<body>
    <!-- Begin page -->
    <div class="wrapper">

        <!-- Menu -->
        <!-- Sidenav Menu Start -->
        @include('common.sidenav')

        <!-- Color Top line -->
        <div class="color-line"></div>

        <!-- Topbar Start -->
        <header class="app-topbar">
            <div class="page-container topbar-menu">
                <div class="d-flex align-items-center gap-2">

                    <!-- Brand Logo -->
                    <a href="{{ route('store.dashboard') }}" class="logo">
                        <span class="logo-light">
                            <span class="logo-lg"><img src="{{ $siteLogo }}" alt="logo"></span>
                            <span class="logo-sm"><img src="{{ $siteLogo }}" alt="small logo"></span>
                        </span>

                        <span class="logo-dark">
                            <span class="logo-lg"><img src="{{ $siteLogo }}" alt="dark logo"></span>
                            <span class="logo-sm"><img src="{{ $siteLogo }}" alt="small logo"></span>
                        </span>
                    </a>

                    <!-- Sidebar Menu Toggle Button -->
                    <button class="sidenav-toggle-button px-2">
                        <i class="ri-menu-5-line fs-24"></i>
                    </button>

                    <!-- Horizontal Menu Toggle Button -->
                    <button class="topnav-toggle-button px-2" data-bs-toggle="collapse" data-bs-target="#topnav-menu-content">
                        <i class="ri-menu-5-line fs-24"></i>
                    </button>

                    <!-- Topbar Page Title -->
                    <div class="topbar-item d-none d-md-flex">
                        

                        
                        <div>
                            <h4 class="page-title fs-18 fw-bold mb-0">Welcome!</h4>
                        </div>
                        
                    </div>
                </div>

                <div class="d-flex align-items-center gap-2">

                    <!-- Search for small devices -->
                    <div class="topbar-item d-flex d-xl-none">
                        <button class="topbar-link" data-bs-toggle="modal" data-bs-target="#searchModal" type="button">
                            <i class="ri-search-line fs-22"></i>
                        </button>
                    </div>

                    <!-- Button Trigger Search Modal -->
                    <!-- <div class="topbar-search d-none d-xl-flex gap-2 me-2 align-items-center" data-bs-toggle="modal"
                        data-bs-target="#searchModal" type="button">
                        <i class="ri-search-line fs-18"></i>
                        <span class="me-2">Search something..</span>
                    </div> -->

                    <!-- Language Dropdown -->
                    <!--  -->

                    <!-- Notification Dropdown -->
                    @include('common.notifications-dropdown', ['rolePrefix' => 'store'])

                    <!-- Button Trigger Customizer Offcanvas -->
                    <!-- <div class="topbar-item d-none d-sm-flex">
                        <button class="topbar-link" data-bs-toggle="offcanvas" data-bs-target="#theme-settings-offcanvas"
                            type="button">
                            <i class="ri-settings-4-line fs-22"></i>
                        </button>
                    </div> -->

                    <!-- Light/Dark Mode Button -->
                    <div class="topbar-item d-none d-sm-flex">
                        <button class="topbar-link" id="light-dark-mode" type="button">
                            <i class="ri-moon-line fs-22"></i>
                        </button>
                    </div>

                    <!-- User Dropdown -->
                    <div class="topbar-item nav-user">
                        <div class="dropdown">
                            <a class="topbar-link dropdown-toggle drop-arrow-none px-2" data-bs-toggle="dropdown"
                                data-bs-offset="0,25" type="button" aria-haspopup="false" aria-expanded="false">
                                <img src="assets/images/users/avatar-1.jpg" width="32" class="rounded-circle me-lg-2 d-flex"
                                    alt="user-image">
                                <span class="d-lg-flex flex-column gap-1 d-none">
                                    <h5 class="my-0">{{ auth()->user()->first_name }} {{ auth()->user()->second_name }}</h5>
                                </span>
                                <i class="ri-arrow-down-s-line d-none d-lg-block align-middle ms-2"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end">
                                <!-- item-->
                                <div class="dropdown-header noti-title">
                                    <h6 class="text-overflow m-0">Welcome !</h6>
                                </div>

                                <!-- item-->
                                <a href="{{ route('store.profile.edit') }}" class="dropdown-item">
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
                </div>
            </div>
        </header>
        <!-- Topbar End -->

        <!-- ============================================================== -->
        <!-- Start Page Content here -->
        <!-- ============================================================== -->

        <div class="page-content">
            <div class="page-container">
                {{ $slot }}
            </div> <!-- container -->

            @include('common.footer')


        </div>

        <!-- ============================================================== -->
        <!-- End Page content -->
        <!-- ============================================================== -->

    </div>
    <!-- END wrapper -->

    <script src="assets/js/vendor.min.js"></script>

    <script src="assets/js/app.js"></script>

    <script src="assets/vendor/apexcharts/apexcharts.min.js"></script>

    <script src="assets/js/pages/dashboard.js"></script>

    {{-- Per-page extra JS (e.g. DataTables) --}}
    @stack('scripts')

</body>
</html>