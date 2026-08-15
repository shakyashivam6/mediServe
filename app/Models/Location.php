<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Self-referencing location tree (see LocationsTableSeeder): India (id=1) ->
 * States (parent_id=1) -> Cities/Districts (parent_id=state id) -> a few
 * localities under some cities (parent_id=city id). Used for the State/City
 * selects on registration forms.
 */
class Location extends Model
{
    protected $table = 'locations';

    public $timestamps = false;
}
