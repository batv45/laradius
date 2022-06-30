<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HotspotAccount extends Model
{
    protected $fillable = [
      'first_name',
      'last_name',
      'birth_year',
      'phone_number',
      'hotspot_id',
      'identity_number',
      'identity_verified_at'
    ];

    public function getUsernameAttribute()
    {
        return $this->id . '@laradius.net';
    }
}
