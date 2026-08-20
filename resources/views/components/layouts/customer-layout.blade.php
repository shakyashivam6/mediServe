{{--
    Customer-facing layout — deliberately its own thing, not the HighDmin
    admin theme (see store-layout.blade.php/admin-layout.blade.php). This is
    a public, mobile-first storefront surface, not an internal dashboard, so
    it gets its own light inline CSS instead of the Bootstrap admin chrome.
--}}
@props(['title' => 'MediServe'])
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>{{ $title }} | {{ config('app.name', 'MediServe') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
        :root {
            --ink: #0f172a;
            --ink-soft: #475569;
            --ink-faint: #94a3b8;
            --bg: #f6f8fb;
            --panel: #ffffff;
            --line: #e2e8f0;
            --teal: #0d9488;
            --teal-soft: #ccfbf1;
            --indigo: #4f46e5;
            --blue: #2563eb;
            --blue-soft: #dbeafe;
            --amber: #d97706;
            --amber-soft: #fef3c7;
            --green: #16a34a;
            --green-soft: #dcfce7;
            --red: #dc2626;
            --red-soft: #fee2e2;
            --radius: 14px;
        }

        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: 'Segoe UI', Figtree, -apple-system, BlinkMacSystemFont, Roboto, Helvetica, Arial, sans-serif;
            background: var(--bg);
            color: var(--ink);
            line-height: 1.55;
        }
        a { color: inherit; }

        .topbar {
            position: sticky; top: 0; z-index: 20;
            background: rgba(246, 248, 251, 0.94);
            backdrop-filter: blur(6px);
            border-bottom: 1px solid var(--line);
        }
        .topbar .wrap {
            max-width: 560px; margin: 0 auto; padding: 14px 20px;
            display: flex; align-items: center; justify-content: space-between; gap: 12px;
        }
        .brand { display: flex; align-items: center; gap: 8px; font-weight: 700; font-size: 15px; }
        .brand .dot { width: 10px; height: 10px; border-radius: 50%; background: linear-gradient(135deg, var(--blue), var(--indigo)); }
        .topbar nav { display: flex; align-items: center; gap: 14px; font-size: 13px; }
        .topbar nav a { text-decoration: none; color: var(--ink-soft); font-weight: 600; }
        .topbar nav a:hover { color: var(--ink); }
        .topbar form { margin: 0; }
        .topbar button.link-btn {
            display: inline-flex; align-items: center;
            background: none; border: none; padding: 0; font: inherit; cursor: pointer;
            color: var(--ink-soft); font-weight: 600; font-size: 13px;
        }
        .topbar button.link-btn:hover { color: var(--red); }
        .topbar nav a { position: relative; }
        .nav-badge {
            display: inline-flex; align-items: center; justify-content: center;
            min-width: 16px; height: 16px; padding: 0 4px; margin-left: 3px;
            border-radius: 999px; background: var(--red); color: #fff;
            font-size: 10.5px; font-weight: 700; line-height: 16px; vertical-align: 2px;
        }
        .bell-link { display: inline-flex; align-items: center; }
        .bell-dot {
            position: absolute; top: -3px; right: -3px;
            width: 8px; height: 8px; border-radius: 50%;
            background: var(--red); border: 1.5px solid var(--bg);
        }

        main.wrap { max-width: 560px; margin: 0 auto; padding: 24px 20px 60px; }

        .card { background: var(--panel); border: 1px solid var(--line); border-radius: var(--radius); padding: 22px; }
        .card + .card { margin-top: 16px; }

        .alert { border-radius: 10px; padding: 12px 16px; font-size: 13.5px; margin-bottom: 16px; }
        .alert-success { background: var(--green-soft); color: #166534; }
        .alert-error { background: var(--red-soft); color: #991b1b; }
        .alert-info { background: var(--blue-soft); color: #1d4ed8; }

        label { display: block; font-size: 13px; font-weight: 600; color: var(--ink-soft); margin: 14px 0 6px; }
        label:first-of-type { margin-top: 0; }
        input[type=text], input[type=tel], input[type=number], textarea, select {
            width: 100%; padding: 11px 14px; border: 1px solid var(--line); border-radius: 10px;
            font-size: 14px; font-family: inherit; color: var(--ink); background: #fff;
        }
        input:focus, textarea:focus, select:focus { outline: 2px solid var(--blue); outline-offset: 1px; }
        textarea { resize: vertical; min-height: 80px; }
        .field-error { color: var(--red); font-size: 12.5px; margin-top: 5px; }
        .hint { color: var(--ink-faint); font-size: 12.5px; margin-top: 5px; }

        .btn {
            display: inline-flex; align-items: center; justify-content: center; gap: 6px;
            background: var(--blue); color: #fff; border: none; border-radius: 10px;
            padding: 12px 20px; font-size: 14px; font-weight: 700; cursor: pointer; text-decoration: none;
            width: 100%; margin-top: 18px;
        }
        .btn:hover { background: var(--indigo); }
        .btn.btn-soft { background: var(--blue-soft); color: var(--blue); }
        .btn.btn-soft:hover { background: #c7d9fb; }
        .btn:disabled { opacity: .6; cursor: not-allowed; }

        .status-badge { display: inline-block; font-size: 11.5px; font-weight: 700; padding: 4px 10px; border-radius: 999px; text-transform: uppercase; letter-spacing: .03em; }
        .status-pending { background: var(--amber-soft); color: #92400e; }
        .status-reviewing, .status-contacted, .status-awaiting_confirmation { background: var(--blue-soft); color: #1d4ed8; }
        .status-confirmed, .status-dispatched { background: var(--green-soft); color: #166534; }
        .status-rejected { background: var(--red-soft); color: #991b1b; }

        footer { max-width: 560px; margin: 0 auto; padding: 10px 20px 40px; font-size: 12px; color: var(--ink-faint); text-align: center; }
    </style>
</head>

<body>
    <div class="topbar">
        <div class="wrap">
            <div class="brand"><span class="dot"></span> {{ config('app.name', 'MediServe') }}</div>
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
                    <a href="{{ route('customer.prescriptions.index') }}">
                        My Prescriptions
                        @if ($unreadNotificationCount > 0)
                            <span class="nav-badge">{{ $unreadNotificationCount }}</span>
                        @endif
                    </a>
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
</body>
</html>
