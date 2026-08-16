{{--
    Shared bell-icon dropdown for the Store and Captain topbars (HighDmin
    theme) — the original theme markup here was 5 hardcoded dummy items
    (fake names/avatars); this replaces that with the logged-in user's own
    real database notifications (see App\Notifications\
    PrescriptionEventNotification and NotificationController). Included
    with a required `$rolePrefix` ('store' or 'captain') so it can build
    the right route names without duplicating this whole block per layout.
--}}
@php
    $recentNotifications = auth()->user()->notifications()->latest()->take(8)->get();
    $unreadNotificationCount = auth()->user()->unreadNotifications()->count();
@endphp
{{-- .noti-icon-badge ships with no rule at all in this theme's compiled
     CSS (it's dead markup carried over from the demo) — these few lines
     are what actually make the dot visible. --}}
<style>
    .topbar-item .topbar-link { position: relative; }
    .noti-icon-badge {
        position: absolute;
        top: 6px;
        right: 8px;
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #fa5c7c;
        border: 1.5px solid var(--highdmin-topbar-bg, #fff);
    }
</style>
<div class="topbar-item">
    <div class="dropdown">
        <button class="topbar-link dropdown-toggle drop-arrow-none" data-bs-toggle="dropdown"
            data-bs-offset="0,25" type="button" data-bs-auto-close="outside" aria-haspopup="false"
            aria-expanded="false">
            <i class="ri-notification-snooze-line animate-ring fs-22"></i>
            @if ($unreadNotificationCount > 0)
                <span class="noti-icon-badge"></span>
            @endif
        </button>

        <div class="dropdown-menu p-0 dropdown-menu-end dropdown-menu-lg" style="min-height: 300px;">
            <div class="p-2 border-bottom position-relative border-dashed">
                <div class="row align-items-center">
                    <div class="col">
                        <h6 class="m-0 fs-16 fw-semibold">
                            Notifications
                            @if ($unreadNotificationCount > 0)
                                <span class="badge bg-danger rounded-pill align-middle">{{ $unreadNotificationCount }}</span>
                            @endif
                        </h6>
                    </div>
                    @if ($unreadNotificationCount > 0)
                        <div class="col-auto">
                            <form method="POST" action="{{ route($rolePrefix.'.notifications.mark-all-read') }}">
                                @csrf
                                <button type="submit" class="btn btn-link btn-sm p-0 text-decoration-underline">Mark all read</button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>

            <div class="position-relative rounded-0" style="max-height: 300px;" data-simplebar>
                @forelse ($recentNotifications as $notification)
                    <a href="{{ route($rolePrefix.'.notifications.open', $notification->id) }}"
                        class="dropdown-item notification-item py-2 text-wrap{{ $notification->read_at ? '' : ' active' }}">
                        <span class="d-flex align-items-center">
                            <div class="avatar-lg flex-shrink-0 me-3">
                                <span class="avatar-title bg-{{ $notification->data['color'] ?? 'primary' }}-subtle text-{{ $notification->data['color'] ?? 'primary' }} rounded-circle fs-22">
                                    <i class="{{ $notification->data['icon'] ?? 'ri-notification-3-line' }}"></i>
                                </span>
                            </div>
                            <span class="flex-grow-1 text-muted">
                                <span class="fw-medium text-body">{{ $notification->data['title'] ?? 'Notification' }}</span>
                                <br />
                                {{ \Illuminate\Support\Str::limit($notification->data['body'] ?? '', 70) }}
                                <br />
                                <span class="fs-12">{{ $notification->created_at->diffForHumans() }}</span>
                            </span>
                        </span>
                    </a>
                @empty
                    <div class="text-center text-muted py-5 fs-13">
                        <i class="ri-notification-off-line fs-32 d-block mb-2"></i>
                        No notifications yet.
                    </div>
                @endforelse
            </div>

            <a href="{{ route($rolePrefix.'.notifications.index') }}"
                class="dropdown-item position-absolute bottom-0 notification-item text-center text-reset text-decoration-underline fw-bold notify-item border-top border-light py-2">
                View All
            </a>
        </div>
    </div>
</div>
