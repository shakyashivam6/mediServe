<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

#[Fillable([
    'first_name',
    'second_name',
    'login_id',
    'mobile',
    'alternate_mobile',
    'adhaar',
    'pan',
    'email',
    'gender',
    'otp',
    'otp_expires_at',
    'dob',
    'city',
    'state',
    'pincode',
    'address',
    'address_line',
    'role',
    'store_id',
    'vehicle_type',
    'isActive',
    'password',
    'email_verified_at',
])]
#[Hidden(['password', 'remember_token', 'otp'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, HasRoles, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'dob' => 'date',
            'isActive' => 'boolean',
            'otp_expires_at' => 'datetime',
        ];
    }

    /**
     * The Store profile for this user, when role=store.
     */
    public function store()
    {
        return $this->hasOne(Store::class);
    }

    /**
     * The Store (another User, role=store) this Captain delivers for.
     */
    public function parentStore()
    {
        return $this->belongsTo(User::class, 'store_id');
    }

    /**
     * This Store's own Captains, when role=store.
     */
    public function captains()
    {
        return $this->hasMany(User::class, 'store_id');
    }

    /**
     * Prescriptions this Customer has uploaded, when role=customer.
     */
    public function prescriptions()
    {
        return $this->hasMany(Prescription::class, 'user_id');
    }

    /**
     * Prescriptions this Store has claimed/is handling, when role=store.
     */
    public function handledPrescriptions()
    {
        return $this->hasMany(Prescription::class, 'store_id');
    }

    /**
     * Prescriptions this Captain has been assigned to deliver, when
     * role=captain.
     */
    public function assignedPrescriptions()
    {
        return $this->hasMany(Prescription::class, 'captain_id');
    }

    /**
     * A Customer's OTP signup only ever supplies a mobile number (see
     * project memory: customer-otp-profile-completion) — this is the gate
     * checked right after OTP verify to decide between the mandatory
     * profile-completion form and going straight to the dashboard, and
     * again by the `customer_profile_complete` middleware on every other
     * Customer route. Deliberately a small, fixed set: identity (name),
     * a backup contact number, and a deliverable postal address — not
     * every KYC-style field Admin-created accounts carry.
     */
    public function hasCompleteProfile(): bool
    {
        return filled($this->first_name)
            && filled($this->alternate_mobile)
            && filled($this->address_line)
            && filled($this->pincode);
    }

    /**
     * Next sequential login_id in the "MS0001" format already used by the
     * seeded accounts (see UserSeeder). Scans existing login_ids rather
     * than tracking a counter so it stays correct even if rows are deleted.
     */
    public static function generateLoginId(): string
    {
        $lastNumber = static::query()
            ->where('login_id', 'like', 'MS%')
            ->pluck('login_id')
            ->map(fn (string $loginId) => (int) substr($loginId, 2))
            ->max() ?? 0;

        return 'MS'.str_pad((string) ($lastNumber + 1), 4, '0', STR_PAD_LEFT);
    }
}
