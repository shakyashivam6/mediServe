@props(['title' => 'MediServe'])
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>{{ $title }} | {{ config('app.name', 'MediServe') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/css/searchable-selects.css') }}" rel="stylesheet" type="text/css">
</head>

<body>
    <div class="topbar">
        <div class="wrap">
            <a class="brand" href="{{ route('home') }}" aria-label="MediServe home"><span class="dot"></span><span class="brand-name">medi<span>Serve</span></span></a>
            @auth
                @php
                    $unreadNotificationCount = auth()->user()->unreadNotifications()->count();
                @endphp
                <nav>
                    <a href="{{ route('customer.notifications.index') }}" class="bell-link" aria-label="Notifications">
                        &#128276;
                        @if ($unreadNotificationCount > 0)
                            <span class="bell-dot"></span>
                        @endif
                    </a>
                    <a href="{{ route('orders.index') }}">Your Orders</a>
                    <a href="{{ route('customer.addresses.index') }}">View Address</a>
                    <a href="{{ route('customer.profile.edit') }}">My Details</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="link-btn" aria-label="Log out" title="Log out">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                        </button>
                    </form>
                </nav>
            @endauth
        </div>
    </div>

    <main class="wrap">
        @if (session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
        @endif
        @if ($errors->any())
            {{-- .alert-error already existed in the CSS above but nothing
                 rendered it — every Customer form failure redirected back
                 silently until now. --}}
            <div class="alert alert-error">{{ $errors->first() }}</div>
        @endif

        {{ $slot }}
    </main>

    <footer>{{ config('app.name', 'MediServe') }} — Customer</footer>
    <script src="{{ asset('assets/js/searchable-selects.js') }}"></script>
</body>
</html>
