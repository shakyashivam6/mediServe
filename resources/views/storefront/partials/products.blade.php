<div class="grid" id="product-grid">
    @forelse ($products as $product)
        @include('storefront.partials.product-card', ['product' => $product])
    @empty
        <div class="empty">No products match these filters. Try changing your search or filters.</div>
    @endforelse
</div>
<div class="pages" id="products-pagination">{{ $products->links('storefront.pagination') }}</div>
