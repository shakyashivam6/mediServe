@if ($paginator->hasPages())
    <div class="page-summary" aria-live="polite">
        Showing {{ $paginator->firstItem() }}–{{ $paginator->lastItem() }} of {{ $paginator->total() }} products
    </div>
    <nav aria-label="Product pages">
        @if ($paginator->onFirstPage())
            <span class="page-disabled" aria-disabled="true">Previous</span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="Previous page">Previous</a>
        @endif

        @php
            $start = max(1, $paginator->currentPage() - 2);
            $end = min($paginator->lastPage(), $paginator->currentPage() + 2);
        @endphp
        @if ($start > 1)
            <a href="{{ $paginator->url(1) }}" aria-label="Page 1">1</a>
            @if ($start > 2)<span class="page-ellipsis" aria-hidden="true">…</span>@endif
        @endif
        @for ($page = $start; $page <= $end; $page++)
            @if ($page === $paginator->currentPage())
                <span class="page-current" aria-current="page">{{ $page }}</span>
            @else
                <a href="{{ $paginator->url($page) }}" aria-label="Page {{ $page }}">{{ $page }}</a>
            @endif
        @endfor
        @if ($end < $paginator->lastPage())
            @if ($end < $paginator->lastPage() - 1)<span class="page-ellipsis" aria-hidden="true">…</span>@endif
            <a href="{{ $paginator->url($paginator->lastPage()) }}" aria-label="Page {{ $paginator->lastPage() }}">{{ $paginator->lastPage() }}</a>
        @endif

        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="Next page">Next</a>
        @else
            <span class="page-disabled" aria-disabled="true">Next</span>
        @endif
    </nav>
@endif
