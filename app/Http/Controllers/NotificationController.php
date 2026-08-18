<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;

/**
 * One controller shared by the Store, Captain and Customer notification
 * routes (see routes/web.php — each role gets its own prefixed route names
 * pointing at the same three actions here) rather than three near-
 * identical copies: reading/marking-read a Laravel database notification
 * is exactly the same operation regardless of which panel it's viewed
 * from, only the view template differs (each panel has its own theme —
 * see index() below). Admin is deliberately not wired in here — this
 * feature was scoped to Store/Captain/Customer only.
 */
class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $notifications = $request->user()->notifications()->latest()->paginate(20);

        $view = match ($request->user()->role) {
            'store' => 'Store.notifications.index',
            'captain' => 'Captain.notifications.index',
            'customer' => 'Customer.notifications.index',
        };

        return view($view, ['notifications' => $notifications]);
    }

    /**
     * The bell dropdown and the notifications list both link straight
     * here rather than to the prescription itself — marking read has to
     * happen somewhere, and folding it into the click-through (a GET,
     * same as every other "open a page" link in this app) means no extra
     * JS/AJAX call is needed just to flip read_at before navigating.
     */
    public function open(Request $request, DatabaseNotification $notification)
    {
        $user = $request->user();

        abort_unless(
            $notification->notifiable_type === $user::class && (int) $notification->notifiable_id === $user->id,
            404
        );

        if (! $notification->read_at) {
            $notification->markAsRead();
        }

        return redirect($notification->data['url'] ?? $this->fallbackRoute($request));
    }

    public function markAllRead(Request $request)
    {
        $request->user()->unreadNotifications->markAsRead();

        return back();
    }

    protected function fallbackRoute(Request $request): string
    {
        return match ($request->user()->role) {
            'store' => route('store.dashboard', absolute: false),
            'captain' => route('captain.dashboard', absolute: false),
            default => route('customer.prescriptions.index', absolute: false),
        };
    }
}
