<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'user_id',
    'store_id',
    'captain_id',
    'files',
    'remark',
    'delivery_address',
    'latitude',
    'longitude',
    'status',
    'items',
    'total_amount',
    'call_notes',
    'called_at',
    'reviewed_at',
    'customer_decided_at',
    'rejection_remark',
    'payment_method',
    'payment_status',
    'delivered_at',
    'collected_at',
    'settled_at',
])]
class Prescription extends Model
{
    protected function casts(): array
    {
        return [
            'files' => 'array',
            'items' => 'array',
            'latitude' => 'float',
            'longitude' => 'float',
            'total_amount' => 'decimal:2',
            'called_at' => 'datetime',
            'reviewed_at' => 'datetime',
            'customer_decided_at' => 'datetime',
            'delivered_at' => 'datetime',
            'collected_at' => 'datetime',
            'settled_at' => 'datetime',
        ];
    }

    /**
     * The Customer (User, role=customer) who uploaded this.
     */
    public function customer()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * The Store (User, role=store) that claimed this — null until one
     * opens it (shared-queue routing, see the create_prescriptions_table
     * migration).
     */
    public function store()
    {
        return $this->belongsTo(User::class, 'store_id');
    }

    /**
     * The Captain (User, role=captain) assigned to deliver this, when
     * dispatched through the software rather than handed off externally.
     */
    public function captain()
    {
        return $this->belongsTo(User::class, 'captain_id');
    }

    /**
     * Chat thread between the Customer and the claiming Store, oldest
     * first — see PrescriptionMessage.
     */
    public function messages()
    {
        return $this->hasMany(PrescriptionMessage::class)->oldest();
    }

    /**
     * Shop profile (shop_name, lat/long, ...) of the claiming Store — one
     * hop past `store`, which is the login/User row. Same two-step shape
     * as User::store() elsewhere in the app.
     */
    public function getStoreProfileAttribute(): ?Store
    {
        return $this->store?->store;
    }

    /**
     * Straight-line distance (km) from the claiming Store to where this
     * Prescription's location was captured — null unless both sides have
     * coordinates (Stores don't reliably have lat/long set yet, and a
     * Customer can submit without sharing location at all).
     */
    public function distanceFromStoreKm(): ?float
    {
        $storeProfile = $this->store_profile;

        if (! $storeProfile || ! $storeProfile->latitude || ! $storeProfile->longitude
            || ! $this->latitude || ! $this->longitude) {
            return null;
        }

        return self::haversineKm(
            (float) $this->latitude,
            (float) $this->longitude,
            (float) $storeProfile->latitude,
            (float) $storeProfile->longitude
        );
    }

    /**
     * Great-circle distance between two lat/long points, in kilometres.
     */
    public static function haversineKm(float $lat1, float $lon1, float $lat2, float $lon2): float
    {
        $earthRadiusKm = 6371;

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) ** 2
            + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLon / 2) ** 2;

        return $earthRadiusKm * (2 * atan2(sqrt($a), sqrt(1 - $a)));
    }

    /**
     * Plain Google Maps link (no API key needed) — this is the "share
     * location manually to a Captain outside the software" mechanism:
     * copy/forward this URL over WhatsApp/SMS/call to anyone, in-app
     * account or not.
     */
    public function googleMapsUrl(): ?string
    {
        return $this->latitude && $this->longitude
            ? "https://www.google.com/maps?q={$this->latitude},{$this->longitude}"
            : null;
    }

    /**
     * Customer-facing wording for the status badge — internal statuses are
     * named for how the Store thinks about them (`dispatched`), which isn't
     * always the clearest word for the Customer looking at their own order.
     */
    public function customerStatusLabel(): string
    {
        return match ($this->status) {
            'dispatched' => 'Out for Delivery',
            default => ucfirst(str_replace('_', ' ', $this->status)),
        };
    }

    public function isCod(): bool
    {
        return $this->payment_method === 'cod';
    }

    /**
     * True while this is a COD order the Captain hasn't collected cash for
     * yet — the "pending amount" state the Customer/Store both need
     * flashed clearly, per the payment_status progression documented on
     * the add_payment_and_delivery_fields_to_prescriptions_table migration.
     */
    public function isCodAmountPending(): bool
    {
        return $this->isCod() && $this->payment_status === 'pending';
    }

    /**
     * True once the Captain has the cash in hand but hasn't yet physically
     * handed it back to the Store (Store's own "Settle" action clears
     * this).
     */
    public function isCodAwaitingSettlement(): bool
    {
        return $this->isCod() && $this->payment_status === 'collected';
    }

    /**
     * One line of payment wording shared by both the Customer and Store
     * views, so "pending"/"collected"/"settled" reads the same everywhere.
     */
    public function paymentStatusLabel(): string
    {
        if (! $this->payment_method) {
            return '—';
        }

        if ($this->payment_method === 'prepaid') {
            return 'Prepaid';
        }

        return match ($this->payment_status) {
            'pending' => 'COD — Pending',
            'collected' => 'COD — Collected',
            'settled' => 'COD — Settled',
            default => 'COD',
        };
    }
}
