<x-layouts.customer-layout title="Notifications">

    <div class="card" style="margin-bottom: 16px;">
        <div style="display:flex; align-items:center; justify-content:space-between; gap:10px;">
            <h2 style="margin:0;">Notifications</h2>
            @if ($notifications->contains(fn ($n) => $n->read_at === null))
                <form method="POST" action="{{ route('customer.notifications.mark-all-read') }}">
                    @csrf
                    <button type="submit" class="btn btn-soft" style="width:auto; margin:0; padding:8px 14px; font-size:13px;">Mark all read</button>
                </form>
            @endif
        </div>
    </div>

    @forelse ($notifications as $notification)
        <a href="{{ route('customer.notifications.open', $notification->id) }}" class="card" style="display:block; text-decoration:none; color:inherit; {{ $notification->read_at ? '' : 'border-color:var(--blue); background:var(--blue-soft);' }}">
            <div style="display:flex; gap:12px; align-items:flex-start;">
                <div style="flex-shrink:0; width:34px; height:34px; border-radius:50%; background:var(--panel); display:flex; align-items:center; justify-content:center; font-size:16px;">
                    🔔
                </div>
                <div style="flex:1; min-width:0;">
                    <div style="display:flex; align-items:center; justify-content:space-between; gap:8px;">
                        <strong style="font-size:14.5px;">{{ $notification->data['title'] ?? 'Notification' }}</strong>
                        @unless ($notification->read_at)
                            <span class="status-badge status-pending" style="flex-shrink:0;">New</span>
                        @endunless
                    </div>
                    <p style="margin:4px 0 0; font-size:13.5px; color:var(--ink-soft);">{{ $notification->data['body'] ?? '' }}</p>
                    <p class="hint" style="margin-top:6px;">{{ $notification->created_at->diffForHumans() }}</p>
                </div>
            </div>
        </a>
    @empty
        <div class="card" style="text-align:center; color:var(--ink-faint); padding:40px 20px;">
            <div style="font-size:28px; margin-bottom:8px;">🔔</div>
            No notifications yet.
        </div>
    @endforelse

    @if ($notifications->hasPages())
        {{-- A hand-rolled prev/next pager rather than $notifications->links()
             — that ships Bootstrap/Tailwind markup, and this layout
             deliberately loads neither (see the layout file's own doc
             comment), so it'd render unstyled. --}}
        <div style="display:flex; justify-content:space-between; gap:10px; margin-top:16px;">
            @if ($notifications->onFirstPage())
                <span></span>
            @else
                <a href="{{ $notifications->previousPageUrl() }}" class="btn btn-soft" style="width:auto; margin:0; padding:9px 16px; font-size:13px;">&larr; Newer</a>
            @endif

            <span class="hint" style="align-self:center;">Page {{ $notifications->currentPage() }} of {{ $notifications->lastPage() }}</span>

            @if ($notifications->hasMorePages())
                <a href="{{ $notifications->nextPageUrl() }}" class="btn btn-soft" style="width:auto; margin:0; padding:9px 16px; font-size:13px;">Older &rarr;</a>
            @else
                <span></span>
            @endif
        </div>
    @endif

</x-layouts.customer-layout>
