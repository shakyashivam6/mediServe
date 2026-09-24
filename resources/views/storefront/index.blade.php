<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
    <link href="{{ asset('assets/css/searchable-selects.css') }}" rel="stylesheet">
    <title>MediServe — Pharmacy essentials</title>
    <style>
        :root{--green:#087f5b;--ink:#18332c;--muted:#70817c;--line:#e6eeeb;--soft:#f5faf8}*{box-sizing:border-box}body{margin:0;background:#fff;color:var(--ink);font:15px/1.5 Inter,ui-sans-serif,system-ui,-apple-system,"Segoe UI",sans-serif}a{color:inherit;text-decoration:none}.wrap{width:min(1180px,calc(100% - 40px));margin:auto}.top{background:#073d31;color:#eafff7;text-align:center;padding:8px;font-size:12px}.nav{border-bottom:1px solid var(--line);background:#fff;position:sticky;top:0;z-index:3}.navin{height:76px;display:flex;align-items:center;gap:30px}.brand{font-size:23px;font-weight:850;letter-spacing:-1px;color:var(--green);white-space:nowrap}.brand span{color:var(--ink)}.search-wrap{position:relative;display:flex;flex:1;max-width:600px}.search{display:flex;flex:1;width:100%;max-width:none;background:#f4f8f6;border:1px solid var(--line);border-radius:12px;padding:4px 6px 4px 14px}.search input{flex:1;min-width:0;border:0;background:transparent;outline:0;padding:9px;color:var(--ink)}.search button,.btn{background:var(--green);color:white;border:0;border-radius:9px;padding:10px 15px;font-weight:700;cursor:pointer}.search-suggestions{position:absolute;top:calc(100% + 7px);left:0;right:0;z-index:10;overflow:hidden;background:white;border:1px solid var(--line);border-radius:12px;box-shadow:0 14px 32px #163e3020}.search-suggestions[hidden]{display:none}.suggestion{display:flex;flex-direction:column;gap:2px;width:100%;padding:10px 15px;text-align:left;background:white;border:0;border-bottom:1px solid #eef3f0;color:var(--ink);cursor:pointer}.suggestion:last-child{border-bottom:0}.suggestion:hover,.suggestion:focus{background:#f2faf6;outline:0}.suggestion-name{font-weight:700}.suggestion-detail{font-size:12px;color:var(--muted)}.suggestion-empty{padding:12px 15px;color:var(--muted);font-size:13px}.navlinks{display:flex;align-items:center;gap:20px;white-space:nowrap;font-weight:650}.hero{margin:28px auto 22px;background:linear-gradient(110deg,#e9f7ef,#f4fbf5 58%,#ddf4e9);border-radius:22px;padding:38px 48px;display:flex;align-items:center;justify-content:space-between;min-height:220px}.hero h1{font-size:clamp(28px,4vw,43px);line-height:1.12;letter-spacing:-1.5px;margin:8px 0 12px;max-width:540px}.hero p{color:var(--muted);margin:0 0 20px}.pill{color:var(--green);font-size:12px;font-weight:800;text-transform:uppercase;letter-spacing:1.4px}.hero-art{font-size:90px;padding:12px 45px}.section-head{display:flex;align-items:end;justify-content:space-between;margin:27px 0 16px}.section-head h2{margin:0;font-size:24px;letter-spacing:-.5px}.sub{color:var(--muted);margin:4px 0 0}.grid{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:17px}.card{position:relative;border:1px solid var(--line);border-radius:16px;overflow:hidden;transition:transform .18s,box-shadow .18s;background:white}.card:hover{transform:translateY(-3px);box-shadow:0 12px 30px #153e3010}.photo{height:185px;background:var(--soft);display:flex;align-items:center;justify-content:center;overflow:hidden}.photo img{width:100%;height:100%;object-fit:contain;padding:15px}.photo .emoji{font-size:53px;opacity:.55}.heart{position:absolute;top:12px;right:12px;width:36px;height:36px;border:1px solid var(--line);border-radius:50%;background:#fff;font-size:19px;cursor:pointer;color:#7d8e88}.heart.saved{color:#e34864}.cardbody{padding:15px}.maker{font-size:11px;color:var(--muted);text-transform:uppercase;letter-spacing:.7px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}.pname{font-size:15px;font-weight:750;line-height:1.35;margin:6px 0;min-height:40px}.pack{font-size:12px;color:var(--muted);min-height:18px}.prices{display:flex;align-items:baseline;gap:8px;margin:12px 0}.price{font-size:19px;font-weight:800}.mrp{font-size:12px;color:#9aa8a3;text-decoration:line-through}.rx{font-size:10px;font-weight:750;color:#925b0e;background:#fff4dd;padding:4px 7px;border-radius:20px}.buy{width:100%;border-radius:9px;padding:10px;background:#e7f5ee;color:var(--green);font-weight:750;border:0;cursor:pointer}.buy:hover{background:var(--green);color:#fff}.notice{margin:14px auto 0;padding:11px 15px;background:#e8f8ee;border-radius:10px;color:#176b49}.empty{grid-column:1/-1;border:1px dashed #cbdad4;border-radius:16px;padding:45px;text-align:center;color:var(--muted)}.pages{margin:28px 0}.pages nav{display:flex;justify-content:center}.footer{margin-top:55px;background:#f5faf8;padding:28px 0;color:var(--muted);font-size:13px}.mobile-actions{display:none}
        .product-gallery{position:relative}.product-gallery .gallery-image{position:absolute;inset:0;opacity:0;transition:opacity .32s ease}.product-gallery .gallery-image.is-active{opacity:1}.product-gallery .gallery-open{position:absolute;z-index:1;inset:0}.gallery-dots{position:absolute;z-index:2;bottom:9px;left:0;right:0;display:flex;justify-content:center;gap:5px;pointer-events:none}.gallery-dot{width:6px;height:6px;border-radius:50%;background:#b8c9c1;box-shadow:0 1px 3px #18332c33}.gallery-dot.is-active{width:15px;border-radius:5px;background:var(--green)}.product-title-link{color:inherit}.product-title-link:hover{color:var(--green)}
        .filterbar{display:grid;grid-template-columns:minmax(180px,1.5fr) repeat(4,minmax(130px,1fr)) auto;gap:10px;align-items:end;margin:18px 0 20px;padding:15px;background:#f7faf8;border:1px solid var(--line);border-radius:14px}.filter-field label{display:block;margin:0 0 5px;color:var(--muted);font-size:11px;font-weight:750;text-transform:uppercase;letter-spacing:.55px}.filter-field select,.filter-field input{width:100%;height:40px;padding:0 10px;border:1px solid #dce8e2;border-radius:8px;background:#fff;color:var(--ink);font:inherit;font-size:13px}.filter-reset{height:40px;padding:0 14px;background:#fff;border:1px solid #dce8e2;border-radius:8px;color:var(--green);font-weight:700;cursor:pointer;white-space:nowrap}.products-results.is-loading{opacity:.48;pointer-events:none;transition:opacity .15s}.page-summary{margin-right:auto}.pages{width:100%}
        @media(max-width:850px){.grid{grid-template-columns:repeat(3,minmax(0,1fr))}.navin{gap:14px}.navlinks{gap:12px}.hero-art{font-size:68px;padding:10px}.filterbar{grid-template-columns:repeat(3,minmax(0,1fr))}}
        .pages{display:flex;align-items:center;justify-content:space-between;gap:16px;padding:8px 2px 0;color:var(--muted);font-size:13px}.pages nav{display:flex;align-items:center;gap:5px}.pages a,.pages span.page-current,.pages span.page-disabled{display:inline-flex;align-items:center;justify-content:center;min-width:38px;height:38px;padding:0 11px;border:1px solid var(--line);border-radius:9px;background:#fff;color:var(--ink);text-decoration:none}.pages a:hover{border-color:#b7dacb;background:#f3faf6;color:var(--green)}.pages span.page-current{border-color:var(--green);background:var(--green);color:#fff;font-weight:750}.pages span.page-disabled{color:#a1afaa;background:#f8faf9}.pages .page-ellipsis{border:0;background:transparent;min-width:22px;padding:0}.qty-control{height:40px;display:flex;align-items:center;justify-content:space-between;border:1px solid #c6e4d5;border-radius:9px;background:#f4faf6;color:var(--ink);overflow:hidden}.qty-control button{width:42px;height:100%;border:0;background:transparent;color:var(--green);font-size:20px;font-weight:750;cursor:pointer}.qty-control button:hover{background:#e3f4ea}.qty-control strong{font-size:14px;min-width:24px;text-align:center}.cart-feedback{position:fixed;right:20px;bottom:20px;z-index:5;padding:12px 18px;border-radius:10px;background:#073d31;color:#fff;box-shadow:0 8px 25px #1238;font-weight:650;opacity:0;transform:translateY(8px);pointer-events:none;transition:.2s}.cart-feedback.visible{opacity:1;transform:translateY(0)}
        @media(max-width:620px){.wrap{width:calc(100% - 24px)}.navin{height:auto;min-height:65px;flex-wrap:wrap;padding:11px 0;gap:9px}.brand{font-size:20px}.search-wrap{order:3;flex-basis:100%;max-width:none}.navlinks{margin-left:auto;font-size:13px}.navlinks .login{display:none}.hero{padding:25px 22px;min-height:190px}.hero-art{font-size:48px;padding:0}.hero h1{font-size:29px}.hero p{font-size:13px}.grid{grid-template-columns:repeat(2,minmax(0,1fr));gap:10px}.photo{height:145px}.cardbody{padding:11px}.pname{font-size:14px}.section-head h2{font-size:21px}.pages{flex-direction:column;gap:9px}.pages a,.pages span.page-current,.pages span.page-disabled{min-width:34px;height:34px;padding:0 9px}.filterbar{grid-template-columns:repeat(2,minmax(0,1fr));gap:9px;padding:11px}.filter-reset{width:100%}}
    </style>
</head>
<body>
    <div class="top">Your health essentials, delivered with care · Secure checkout with mobile OTP</div>
    <header class="nav"><div class="wrap navin">
        <a class="brand" href="{{ route('home') }}">medi<span>Serve</span></a>
        <div class="search-wrap"><form id="product-search" class="search" action="{{ route('home') }}" method="GET"><input id="product-search-input" name="q" value="{{ request('q') }}" placeholder="Search medicines, brands or ingredients" autocomplete="off" aria-autocomplete="list" aria-expanded="false" aria-controls="search-suggestions"><button type="submit">Search</button></form><div id="search-suggestions" class="search-suggestions" role="listbox" hidden></div></div>
        <div class="navlinks"><a href="{{ route('wishlist.index') }}">♡ Wishlist</a><a href="{{ route('cart.index') }}">🛒 Cart (<span id="cart-count">{{ $cartCount }}</span>)</a><a class="login" href="{{ auth()->check() ? (auth()->user()->role === 'customer' ? route('customer.prescriptions.index') : route('dashboard')) : route('login') }}">{{ auth()->check() ? 'Account' : 'Login' }}</a></div>
    </div></header>
    <main class="wrap">
        @if (session('shop_status'))<div class="notice">{{ session('shop_status') }}</div>@endif
        <section class="hero"><div><div class="pill">Care that comes to you</div><h1>Everyday health, made a little easier.</h1><p>Find trusted medicines and wellness essentials in one place.</p><a class="btn" href="{{ route('prescription.upload.start') }}">Upload prescription</a></div><div class="hero-art" aria-hidden="true">💊🌿</div></section>
        <section id="products">
            <div class="section-head"><div><h2 id="products-heading">{{ request('q') ? 'Search results' : 'Popular products' }}</h2><p class="sub"><span id="product-total">{{ $products->total() }}</span> products to support your wellbeing</p></div></div>
            <!-- <form id="product-filters" class="filterbar" action="{{ route('home') }}" method="GET">
                <div class="filter-field"><label for="manufacturer-filter">Brand / Manufacturer</label><select id="manufacturer-filter" name="manufacturer"><option value="">All brands</option>@foreach($manufacturers as $manufacturer)<option value="{{ $manufacturer }}" @selected(request('manufacturer') === $manufacturer)>{{ $manufacturer }}</option>@endforeach</select></div>
                <div class="filter-field"><label for="rx-filter">Product type</label><select id="rx-filter" name="rx"><option value="">All products</option><option value="otc" @selected(request('rx') === 'otc')>Non-prescription</option><option value="rx" @selected(request('rx') === 'rx')>Prescription required</option></select></div>
                <div class="filter-field"><label>Minimum price (₹)</label><input type="number" name="min_price" min="0" step="0.01" value="{{ request('min_price') }}" placeholder="No minimum"></div>
                <div class="filter-field"><label>Maximum price (₹)</label><input type="number" name="max_price" min="0" step="0.01" value="{{ request('max_price') }}" placeholder="No maximum"></div>
                <div class="filter-field"><label for="packaging-filter">Pack size</label><input id="packaging-filter" type="search" name="packaging" value="{{ request('packaging') }}" placeholder="e.g. 1 strip"></div>
                <div class="filter-field"><label for="deal-filter">Offers</label><select id="deal-filter" name="deal"><option value="">All prices</option><option value="discounted" @selected(request('deal') === 'discounted')>Discounted only</option></select></div>
                <div class="filter-field"><label for="sort-filter">Sort by</label><select id="sort-filter" name="sort"><option value="">Name: A to Z</option><option value="name_desc" @selected(request('sort') === 'name_desc')>Name: Z to A</option><option value="price_low" @selected(request('sort') === 'price_low')>Price: Low to high</option><option value="price_high" @selected(request('sort') === 'price_high')>Price: High to low</option></select></div>
                <button class="filter-reset" type="reset">Clear filters</button>
            </form>
            <p id="filter-error" class="filter-error" role="alert" hidden></p> -->
            <div id="products-results" class="products-results">
            <div class="grid" id="product-grid">
                @forelse ($products as $product)
                    <article class="card" data-product-id="{{ $product->id }}">
                        <form method="POST" action="{{ route('wishlist.toggle', $product) }}">@csrf<button class="heart {{ in_array($product->id, $savedIds) ? 'saved' : '' }}" aria-label="{{ in_array($product->id, $savedIds) ? 'Remove from wishlist' : 'Add to wishlist' }}">{{ in_array($product->id, $savedIds) ? '♥' : '♡' }}</button></form>
        @php($galleryImages = collect($product->images ?? [])->filter(fn ($image) => filled($image))->unique()->values())
        <div class="photo product-gallery" data-product-gallery>
                        <a class="gallery-open" href="{{ route('products.show', $product) }}" aria-label="View {{ $product->name }} details"></a>
            @if($galleryImages->isNotEmpty())
                @foreach($galleryImages as $image)
                    <img class="gallery-image {{ $loop->first ? 'is-active' : '' }}" data-gallery-image src="{{ $image }}" alt="{{ $product->name }} image {{ $loop->iteration }}" loading="lazy" onerror="this.remove()">
                @endforeach
                @if($galleryImages->count() > 1)<div class="gallery-dots" aria-hidden="true">@foreach($galleryImages as $image)<span class="gallery-dot {{ $loop->first ? 'is-active' : '' }}"></span>@endforeach</div>@endif
            @else
                <span class="emoji">🧴</span>
            @endif
        </div>
                        <div class="cardbody"><div class="maker">{{ $product->manufacturer ?: 'MediServe' }}</div><div class="pname"><a class="product-title-link" href="{{ route('products.show', $product) }}">{{ $product->name }}</a></div><div class="pack">{{ $product->packaging ?: ($product->composition ? \Illuminate\Support\Str::limit($product->composition, 36) : 'Healthcare essential') }}</div>
                            <div class="prices"><span class="price">{{ $product->price !== null ? '₹'.number_format((float)$product->price, 2) : 'Price on request' }}</span>@if($product->mrp && $product->price < $product->mrp)<span class="mrp">₹{{ number_format((float)$product->mrp, 2) }}</span>@endif</div>
                            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:10px">@if($product->requires_prescription)<span class="rx">PRESCRIPTION REQUIRED</span>@else<span></span>@endif</div>
                            @php($quantityInCart = (int) ($cartQuantities[$product->id] ?? 0))
                            <form class="cart-add-form" method="POST" action="{{ route('cart.add', $product) }}" data-product-id="{{ $product->id }}">@csrf<button class="buy">{{ $quantityInCart ? 'Add another' : 'Add to cart' }}</button></form>
                            <div class="qty-control product-quantity" data-quantity-url="{{ route('cart.quantity', $product) }}" style="{{ $quantityInCart ? '' : 'display:none' }}"><input type="hidden" name="_token" value="{{ csrf_token() }}"><button type="button" data-qty-step="-1" aria-label="Decrease quantity">−</button><strong data-quantity>{{ $quantityInCart }}</strong><button type="button" data-qty-step="1" aria-label="Increase quantity">+</button></div>
                        </div>
                    </article>
                @empty<div class="empty">No products found. Try a different search.</div>@endforelse
            </div>
            <div class="pages" id="products-pagination">{{ $products->links('storefront.pagination') }}</div>
            </div>
        </section>
    </main>
    <footer class="footer"><div class="wrap">© {{ date('Y') }} MediServe · Your neighbourhood pharmacy, online.</div></footer>
    <div class="cart-feedback" role="status" aria-live="polite"></div>
    @include('storefront.cart-scripts')
    <script src="{{ asset('assets/js/searchable-selects.js') }}"></script>
    <script>
        (() => {
            const timers = new WeakMap();
            const setActive = (gallery, index) => {
                const images = [...gallery.querySelectorAll('[data-gallery-image]')];
                if (!images.length) return;
                index = index % images.length;
                images.forEach((image, imageIndex) => image.classList.toggle('is-active', imageIndex === index));
                gallery.querySelectorAll('.gallery-dot').forEach((dot, dotIndex) => dot.classList.toggle('is-active', dotIndex === index));
            };
            const stop = gallery => {
                clearInterval(timers.get(gallery));
                timers.delete(gallery);
                setActive(gallery, 0);
            };

            document.addEventListener('pointerover', event => {
                if (event.pointerType !== 'mouse') return;
                const gallery = event.target.closest('[data-product-gallery]');
                if (!gallery || (event.relatedTarget && gallery.contains(event.relatedTarget))) return;
                if (gallery.querySelectorAll('[data-gallery-image]').length < 2 || timers.has(gallery)) return;
                let index = 0;
                timers.set(gallery, setInterval(() => setActive(gallery, ++index), 850));
            });

            document.addEventListener('pointerout', event => {
                if (event.pointerType !== 'mouse') return;
                const gallery = event.target.closest('[data-product-gallery]');
                if (gallery && !(event.relatedTarget && gallery.contains(event.relatedTarget))) stop(gallery);
            });
        })();
    </script>
    <script>
        (() => {
            const filterForm = document.getElementById('product-filters');
            const searchForm = document.getElementById('product-search');
            const searchInput = document.getElementById('product-search-input');
            const results = document.getElementById('products-results');
            const total = document.getElementById('product-total');
            const errorMessage = document.getElementById('filter-error');
            let controller;

            const syncControls = url => {
                const params = new URL(url, location.origin).searchParams;
                searchInput.value = params.get('q') || '';
                for (const field of filterForm.elements) {
                    if (!field.name) continue;
                    field.value = params.get(field.name) || '';
                }
            };

            const filterUrl = (page = 1) => {
                const url = new URL(filterForm.action, location.origin);
                const params = new URLSearchParams(new FormData(filterForm));
                if (searchInput.value.trim()) params.set('q', searchInput.value.trim());
                if (page > 1) params.set('page', page);
                url.search = params.toString();
                return url;
            };

            const fetchProducts = async (url, pushState = true) => {
                if (controller) controller.abort();
                controller = new AbortController();
                results.classList.add('is-loading');
                errorMessage.hidden = true;
                try {
                    const response = await fetch(url, {
                        headers: { Accept: 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                        signal: controller.signal,
                    });
                    const data = await response.json();
                    if (!response.ok) throw new Error('Unable to filter products.');
                    results.innerHTML = data.html;
                    total.textContent = data.total;
                    document.getElementById('products-heading').textContent = new URL(url, location.origin).searchParams.has('q') ? 'Search results' : 'Popular products';
                    if (pushState) history.pushState({}, '', url);
                    syncControls(url);
                } catch (error) {
                    if (error.name !== 'AbortError') {
                        errorMessage.textContent = error.message;
                        errorMessage.hidden = false;
                    }
                } finally {
                    results.classList.remove('is-loading');
                }
            };

            window.applyProductFilters = () => fetchProducts(filterUrl());
            filterForm.addEventListener('change', window.applyProductFilters);
            filterForm.addEventListener('submit', event => { event.preventDefault(); window.applyProductFilters(); });
            filterForm.addEventListener('reset', () => setTimeout(window.applyProductFilters, 0));
            searchForm.addEventListener('submit', event => { event.preventDefault(); window.applyProductFilters(); });
            results.addEventListener('click', event => {
                const link = event.target.closest('#products-pagination a[href]');
                if (!link) return;
                event.preventDefault();
                fetchProducts(link.href);
            });
            window.addEventListener('popstate', () => fetchProducts(location.href, false));
        })();
    </script>
    <script>
        (() => {
            const input = document.getElementById('product-search-input');
            const form = document.getElementById('product-search');
            const panel = document.getElementById('search-suggestions');
            let timer;
            let controller;

            const close = () => {
                panel.hidden = true;
                input.setAttribute('aria-expanded', 'false');
            };

            const showEmpty = (message) => {
                panel.replaceChildren();
                const empty = document.createElement('div');
                empty.className = 'suggestion-empty';
                empty.textContent = message;
                panel.append(empty);
                panel.hidden = false;
                input.setAttribute('aria-expanded', 'true');
            };

            input.addEventListener('input', () => {
                clearTimeout(timer);
                if (controller) controller.abort();
                const query = input.value.trim();
                if (query.length < 3) {
                    close();
                    return;
                }

                timer = setTimeout(async () => {
                    controller = new AbortController();
                    showEmpty('Searching products…');
                    try {
                        const url = new URL(@json(route('products.suggestions')), window.location.origin);
                        url.searchParams.set('q', query);
                        const response = await fetch(url, { headers: { Accept: 'application/json' }, signal: controller.signal });
                        if (!response.ok) throw new Error('Search is unavailable.');
                        const { data } = await response.json();
                        panel.replaceChildren();
                        if (!data.length) {
                            showEmpty('No matching products found.');
                            return;
                        }
                        data.forEach(product => {
                            const option = document.createElement('button');
                            option.type = 'button';
                            option.className = 'suggestion';
                            option.setAttribute('role', 'option');
                            option.dataset.productName = product.name;
                            option.dataset.productUrl = product.url;
                            const name = document.createElement('span');
                            name.className = 'suggestion-name';
                            name.textContent = product.name;
                            const detail = document.createElement('span');
                            detail.className = 'suggestion-detail';
                            detail.textContent = [product.manufacturer, product.composition, product.price].filter(Boolean).join(' · ');
                            option.append(name, detail);
                            panel.append(option);
                        });
                        panel.hidden = false;
                        input.setAttribute('aria-expanded', 'true');
                    } catch (error) {
                        if (error.name !== 'AbortError') showEmpty('Could not load product suggestions.');
                    }
                }, 250);
            });

            panel.addEventListener('click', event => {
                const option = event.target.closest('[data-product-name]');
                if (!option) return;
                close();
                window.location.assign(option.dataset.productUrl);
            });
            input.addEventListener('keydown', event => { if (event.key === 'Escape') close(); });
            document.addEventListener('click', event => { if (!event.target.closest('.search-wrap')) close(); });
        })();
    </script>
</body>
</html>
