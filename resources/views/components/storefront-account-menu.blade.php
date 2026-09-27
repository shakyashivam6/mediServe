@if (auth()->check() && auth()->user()->role === 'customer')
    <div class="account-menu" data-account-menu>
        <button class="account-trigger" type="button" aria-haspopup="true" aria-expanded="false" aria-controls="account-dropdown">
            Account <span aria-hidden="true">⌄</span>
        </button>
        <div class="account-dropdown" id="account-dropdown" role="menu" hidden>
            <a role="menuitem" href="{{ route('orders.index') }}">Your Orders</a>
            <a role="menuitem" href="{{ route('customer.profile.edit') }}">View Profile</a>
            <a role="menuitem" href="{{ route('customer.addresses.index') }}">View Address</a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button role="menuitem" type="submit">Sign Out</button>
            </form>
        </div>
    </div>
    <style>
        .account-menu{position:relative;display:inline-flex;align-items:center}
        .account-trigger{display:inline-flex;align-items:center;gap:6px;border:0;background:transparent;padding:9px 2px;color:inherit;font:inherit;font-weight:650;cursor:pointer}
        .account-trigger:focus-visible,.account-dropdown a:focus-visible,.account-dropdown button:focus-visible{outline:2px solid #087f5b;outline-offset:3px;border-radius:5px}
        .account-dropdown{position:absolute;z-index:30;top:calc(100% + 9px);right:0;width:190px;padding:6px;background:#fff;border:1px solid #e6eeeb;border-radius:12px;box-shadow:0 14px 34px #18332c1c;color:#18332c}
        .account-dropdown[hidden]{display:none}
        .account-dropdown a,.account-dropdown button{display:block;width:100%;padding:10px 11px;border:0;border-radius:7px;background:transparent;color:inherit;text-align:left;text-decoration:none;font:inherit;font-size:14px;cursor:pointer}
        .account-dropdown a:hover,.account-dropdown button:hover{background:#f2faf6;color:#087f5b}
        .account-dropdown form{margin:4px 0 0;padding-top:4px;border-top:1px solid #edf2ef}
    </style>
    <script>
        (() => {
            const menu = document.currentScript.previousElementSibling.previousElementSibling;
            if (!menu || !menu.matches('[data-account-menu]')) return;
            const trigger = menu.querySelector('.account-trigger');
            const dropdown = menu.querySelector('.account-dropdown');
            const close = () => { dropdown.hidden = true; trigger.setAttribute('aria-expanded', 'false'); };
            trigger.addEventListener('click', () => {
                dropdown.hidden = !dropdown.hidden;
                trigger.setAttribute('aria-expanded', String(!dropdown.hidden));
            });
            document.addEventListener('click', event => { if (!menu.contains(event.target)) close(); });
            document.addEventListener('keydown', event => {
                if (event.key === 'Escape' && !dropdown.hidden) { close(); trigger.focus(); }
            });
        })();
    </script>
@else
    <a class="account" href="{{ auth()->check() ? route('dashboard') : route('login') }}">{{ auth()->check() ? 'Account' : 'Login' }}</a>
@endif
