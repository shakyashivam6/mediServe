<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
    <title>{{ $product->name }} · MediServe</title>
    <link href="{{ asset('assets/css/searchable-selects.css') }}" rel="stylesheet">
    <style>
        :root{--green:#087f5b;--ink:#18332c;--muted:#70817c;--line:#e6eeeb;--soft:#f5faf8}*{box-sizing:border-box}body{margin:0;background:#fff;color:var(--ink);font:15px/1.55 Inter,ui-sans-serif,system-ui,-apple-system,"Segoe UI",sans-serif}a{color:inherit;text-decoration:none}.wrap{width:min(1180px,calc(100% - 40px));margin:auto}.top{background:#073d31;color:#eafff7;text-align:center;padding:8px;font-size:12px}.nav{border-bottom:1px solid var(--line);background:#fff;position:sticky;top:0;z-index:3}.navin{height:72px;display:flex;align-items:center;justify-content:space-between;gap:22px}.brand{font-size:23px;font-weight:850;letter-spacing:-1px;color:var(--green)}.brand span{color:var(--ink)}.navlinks{display:flex;align-items:center;gap:20px;font-weight:650}.back{color:var(--green);font-weight:700}.crumb{margin:24px 0;color:var(--muted);font-size:13px}.crumb a{color:var(--green)}.detail{display:grid;grid-template-columns:minmax(0,1fr) minmax(0,1fr);gap:48px;align-items:start;margin:18px 0 52px}.gallery{position:relative;height:480px;border:1px solid var(--line);border-radius:20px;background:var(--soft);overflow:hidden}.gallery .gallery-image{position:absolute;inset:0;width:100%;height:100%;object-fit:contain;padding:30px;opacity:0;transition:opacity .3s ease}.gallery .gallery-image.is-active{opacity:1}.gallery-dots{position:absolute;z-index:2;bottom:15px;left:0;right:0;display:flex;justify-content:center;gap:6px;pointer-events:none}.gallery-dot{width:7px;height:7px;border-radius:10px;background:#b8c9c1}.gallery-dot.is-active{width:18px;background:var(--green)}.maker{color:var(--muted);font-size:12px;text-transform:uppercase;letter-spacing:1px}.name{font-size:34px;line-height:1.2;letter-spacing:-.8px;margin:8px 0 10px}.pack{color:var(--muted);margin-bottom:20px}.badge{display:inline-flex;padding:5px 10px;border-radius:20px;background:#e9f7ef;color:var(--green);font-size:11px;font-weight:800;text-transform:uppercase}.badge.rx{background:#fff4dd;color:#925b0e}.price-row{display:flex;align-items:baseline;gap:12px;margin:19px 0}.price{font-size:30px;font-weight:850}.mrp{color:#9aa8a3;text-decoration:line-through}.save{color:var(--green);font-size:13px;font-weight:700}.actions{display:grid;grid-template-columns:1fr 52px;gap:10px;margin:22px 0 16px}.buy{height:48px;border:0;border-radius:10px;background:var(--green);color:#fff;font-size:15px;font-weight:750;cursor:pointer}.wish{height:48px;border:1px solid var(--line);border-radius:10px;background:#fff;color:#dc4962;font-size:23px;cursor:pointer}.qty-control{height:44px;max-width:220px;display:flex;align-items:center;justify-content:space-between;border:1px solid #c6e4d5;border-radius:9px;background:#f4faf6;overflow:hidden}.qty-control button{width:46px;height:100%;border:0;background:transparent;color:var(--green);font-size:21px;font-weight:750;cursor:pointer}.qty-control strong{min-width:28px;text-align:center}.info-box{margin-top:24px;padding:18px;border:1px solid var(--line);border-radius:13px}.info-row{display:flex;gap:12px;padding:8px 0;border-bottom:1px solid #eef3f0}.info-row:last-child{border:0}.info-label{width:125px;flex-shrink:0;color:var(--muted);font-size:13px}.description{margin-top:27px}.description h2{font-size:18px;margin-bottom:8px}.description p{color:#52665e;white-space:pre-line;margin:0}.notice{position:fixed;right:20px;bottom:20px;z-index:5;padding:12px 18px;border-radius:10px;background:#073d31;color:#fff;box-shadow:0 8px 25px #1238;font-weight:650;opacity:0;transform:translateY(8px);pointer-events:none;transition:.2s}.notice.visible{opacity:1;transform:translateY(0)}.footer{background:#f5faf8;padding:27px 0;color:var(--muted);font-size:13px}
        @media(max-width:760px){.detail{grid-template-columns:1fr;gap:26px}.gallery{height:min(90vw,430px)}.name{font-size:29px}.navlinks{gap:11px;font-size:13px}}
        @media(max-width:520px){.wrap{width:calc(100% - 26px)}.navin{height:62px}.brand{font-size:20px}.back{font-size:13px}.navlinks .account{display:none}.crumb{margin:18px 0}.detail{margin-top:10px}.gallery{height:88vw}.name{font-size:26px}.price{font-size:27px}.info-label{width:105px}}
    </style>
</head>
<body>
    <div class="top">Your health essentials, delivered with care · Secure checkout with mobile OTP</div>
    <header class="nav"><div class="wrap navin">
        <a class="brand" href="{{ route('home') }}">medi<span>Serve</span></a>
        <a class="back" href="{{ url()->previous() === route('products.show', $product) ? route('home') : url()->previous() }}">← Back to products</a>
        <div class="navlinks"><a href="{{ route('wishlist.index') }}">♡ Wishlist</a><a href="{{ route('cart.index') }}">🛒 Cart (<span id="cart-count">{{ $cartCount }}</span>)</a><a class="account" href="{{ auth()->check() ? (auth()->user()->role === 'customer' ? route('customer.prescriptions.index') : route('dashboard')) : route('login') }}">{{ auth()->check() ? 'Account' : 'Login' }}</a></div>
    </div></header>
    <main class="wrap" data-product-id="{{ $product->id }}">
        <div class="crumb"><a href="{{ route('home') }}">Home</a> / {{ $product->name }}</div>
        <section class="detail">
            <div class="gallery product-gallery" data-product-gallery>
                @php($galleryImages = collect($product->images ?? [])->filter(fn ($image) => filled($image))->unique()->values())
                @forelse($galleryImages as $image)
                    <img class="gallery-image {{ $loop->first ? 'is-active' : '' }}" data-gallery-image src="{{ $image }}" alt="{{ $product->name }} image {{ $loop->iteration }}" onerror="this.remove()">
                @empty
                    <div style="height:100%;display:grid;place-items:center;font-size:90px" aria-hidden="true">🧴</div>
                @endforelse
                @if($galleryImages->count() > 1)<div class="gallery-dots" aria-hidden="true">@foreach($galleryImages as $image)<span class="gallery-dot {{ $loop->first ? 'is-active' : '' }}"></span>@endforeach</div>@endif
            </div>
            <div>
                <div class="maker">{{ $product->manufacturer ?: 'MediServe' }}</div>
                <h1 class="name">{{ $product->name }}</h1>
                @if($product->packaging)<div class="pack">{{ $product->packaging }}</div>@endif
                <span class="badge {{ $product->requires_prescription ? 'rx' : '' }}">{{ $product->requires_prescription ? 'Prescription required' : 'Non-prescription' }}</span>
                <div class="price-row"><span class="price">{{ $product->price !== null ? '₹'.number_format((float)$product->price, 2) : 'Price on request' }}</span>@if($product->mrp && $product->price !== null && $product->mrp > $product->price)<span class="mrp">₹{{ number_format((float)$product->mrp, 2) }}</span><span class="save">Save {{ number_format((float)(($product->mrp - $product->price) / $product->mrp) * 100, 0) }}%</span>@endif</div>
                <div class="actions">
                    <form class="cart-add-form" method="POST" action="{{ route('cart.add', $product) }}" data-product-id="{{ $product->id }}">@csrf<button class="buy">{{ $quantity ? 'Add another to cart' : 'Add to cart' }}</button></form>
                    <form method="POST" action="{{ route('wishlist.toggle', $product) }}">@csrf<button class="wish" aria-label="{{ $saved ? 'Remove from wishlist' : 'Add to wishlist' }}">{{ $saved ? '♥' : '♡' }}</button></form>
                </div>
                <div class="qty-control product-quantity" data-quantity-url="{{ route('cart.quantity', $product) }}" style="{{ $quantity ? '' : 'display:none' }}"><input type="hidden" name="_token" value="{{ csrf_token() }}"><button type="button" data-qty-step="-1" aria-label="Decrease quantity">−</button><strong data-quantity>{{ $quantity }}</strong><button type="button" data-qty-step="1" aria-label="Increase quantity">+</button></div>
                <div class="info-box">
                    @if($product->composition)<div class="info-row"><span class="info-label">Composition</span><span>{{ $product->composition }}</span></div>@endif
                    @if($product->packaging)<div class="info-row"><span class="info-label">Pack size</span><span>{{ $product->packaging }}</span></div>@endif
                    @if($product->mrp)<div class="info-row"><span class="info-label">MRP</span><span>₹{{ number_format((float)$product->mrp, 2) }}</span></div>@endif
                    <div class="info-row"><span class="info-label">Availability</span><span>Available to order</span></div>
                </div>
                @if($product->uses)<div class="description"><h2>About this product</h2><p>{{ $product->uses }}</p></div>@endif
            </div>
        </section>
    </main>
    <footer class="footer"><div class="wrap">© {{ date('Y') }} MediServe · Your neighbourhood pharmacy, online.</div></footer>
    <div class="notice cart-feedback" role="status" aria-live="polite"></div>
    @include('storefront.cart-scripts')
    <script>
        (() => {
            const gallery = document.querySelector('[data-product-gallery]');
            if (!gallery) return;
            let timer;
            const show = index => {
                const images = [...gallery.querySelectorAll('[data-gallery-image]')];
                if (!images.length) return;
                const active = index % images.length;
                images.forEach((image, imageIndex) => image.classList.toggle('is-active', imageIndex === active));
                gallery.querySelectorAll('.gallery-dot').forEach((dot, dotIndex) => dot.classList.toggle('is-active', dotIndex === active));
            };
            gallery.addEventListener('pointerenter', event => {
                if (event.pointerType !== 'mouse' || gallery.querySelectorAll('[data-gallery-image]').length < 2) return;
                let index = 0;
                timer = setInterval(() => show(++index), 850);
            });
            gallery.addEventListener('pointerleave', event => {
                if (event.pointerType !== 'mouse') return;
                clearInterval(timer);
                show(0);
            });
        })();
    </script>
</body>
</html>
