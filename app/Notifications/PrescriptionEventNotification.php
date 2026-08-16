<?php

namespace App\Notifications;

use App\Models\Prescription;
use Illuminate\Notifications\Notification;

/**
 * One generic, reusable notification for every prescription-lifecycle
 * event — claimed, estimate sent, accepted/rejected, captain assigned,
 * delivered, COD settled, new chat message — rather than a class per
 * event. The shape (title/body/link/icon/colour) is identical throughout;
 * only the wording differs per call site, so a single class parameterised
 * at the call site is far less to maintain than a dozen near-identical
 * classes.
 *
 * Database-only (no `ShouldQueue`): fires synchronously on the same
 * request, since nothing in this app currently runs a queue worker and
 * these are cheap single-row inserts. `icon`/`color` feed the bell
 * dropdown and notifications list — `icon` is a Remix Icon class
 * (`ri-*`, matching every other icon in the Store/Captain panel), `color`
 * a Bootstrap contextual colour (primary/success/danger/info/warning).
 */
class PrescriptionEventNotification extends Notification
{
    public function __construct(
        public Prescription $prescription,
        public string $title,
        public string $body,
        public string $url,
        public string $icon = 'ri-file-text-line',
        public string $color = 'primary',
    ) {}

    /**
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'prescription_id' => $this->prescription->id,
            'title' => $this->title,
            'body' => $this->body,
            'url' => $this->url,
            'icon' => $this->icon,
            'color' => $this->color,
        ];
    }
}
