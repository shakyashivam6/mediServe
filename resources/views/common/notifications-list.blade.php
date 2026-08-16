{{--
    Shared "all notifications" list body for the Store/Captain panels
    (HighDmin theme) — expects `$notifications` (a paginator of database
    notifications) and `$rolePrefix` ('store' or 'captain'). The bell
    dropdown only ever shows the latest 8; this is the full paginated
    history behind "View All".
--}}
<div class="card">
    <div class="card-header d-flex flex-wrap align-items-center gap-2">
        <h4 class="header-title me-auto">Notifications</h4>
        @if ($notifications->contains(fn ($n) => $n->read_at === null))
            <form method="POST" action="{{ route($rolePrefix.'.notifications.mark-all-read') }}">
                @csrf
                <button type="submit" class="btn btn-sm btn-soft-primary">Mark all read</button>
            </form>
        @endif
    </div>

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-custom table-centered table-nowrap mb-0">
                <tbody>
                    @forelse ($notifications as $notification)
                        <tr class="{{ $notification->read_at ? '' : 'table-active' }}">
                            <td style="width: 46px;">
                                <div class="avatar-md flex-shrink-0">
                                    <span class="avatar-title bg-{{ $notification->data['color'] ?? 'primary' }}-subtle text-{{ $notification->data['color'] ?? 'primary' }} rounded-circle fs-20">
                                        <i class="{{ $notification->data['icon'] ?? 'ri-notification-3-line' }}"></i>
                                    </span>
                                </div>
                            </td>
                            <td>
                                <a href="{{ route($rolePrefix.'.notifications.open', $notification->id) }}" class="text-reset">
                                    <h5 class="fs-14 my-1">{{ $notification->data['title'] ?? 'Notification' }}</h5>
                                    <p class="text-muted mb-0 fs-13">{{ $notification->data['body'] ?? '' }}</p>
                                </a>
                            </td>
                            <td class="text-muted fs-12" style="width: 140px;">
                                {{ $notification->created_at->diffForHumans() }}
                                @unless ($notification->read_at)
                                    <br><span class="badge bg-danger-subtle text-danger mt-1">Unread</span>
                                @endunless
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="text-center text-muted py-5">
                                <i class="ri-notification-off-line fs-32 d-block mb-2"></i>
                                No notifications yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if ($notifications->hasPages())
        <div class="card-footer">
            {{-- This app's admin theme is Bootstrap 5; Laravel's default
                 paginator view is Tailwind, which would render mismatched
                 markup here — the framework ships a bootstrap-5 view too. --}}
            {{ $notifications->links('bootstrap-5') }}
        </div>
    @endif
</div>
