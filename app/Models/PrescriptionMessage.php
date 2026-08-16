<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'prescription_id',
    'user_id',
    'body',
])]
class PrescriptionMessage extends Model
{
    public function prescription()
    {
        return $this->belongsTo(Prescription::class);
    }

    /**
     * Whoever sent it — the Customer, or the claiming Store's account.
     * Which side that is comes from comparing this against
     * `prescription->user_id`/`store_id`, not a stored role column.
     */
    public function sender()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
