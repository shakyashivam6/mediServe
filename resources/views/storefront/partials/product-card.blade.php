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
        <form class="cart-add-form" method="POST" action="{{ route('cart.add', $product) }}" data-product-id="{{ $product->id }}" style="{{ $quantityInCart ? 'display:none' : '' }}">@csrf<button class="buy">Add to cart</button></form>
        <div class="qty-control product-quantity" data-quantity-url="{{ route('cart.quantity', $product) }}" data-remove-url="{{ route('cart.remove', $product) }}" style="{{ $quantityInCart ? '' : 'display:none' }}"><input type="hidden" name="_token" value="{{ csrf_token() }}"><button type="button" @if($quantityInCart <= 1) data-qty-remove aria-label="Remove from cart" title="Remove from cart" @else data-qty-step="-1" aria-label="Decrease quantity" @endif>@if($quantityInCart <= 1)<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 6h18"/><path d="M8 6V4h8v2"/><path d="m19 6-1 14H6L5 6"/><path d="M10 11v5M14 11v5"/></svg>@else − @endif</button><strong data-quantity>{{ $quantityInCart }}</strong><button type="button" data-qty-step="1" aria-label="Increase quantity">+</button></div>
    </div>
</article>
