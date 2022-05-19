<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{

    use HasFactory;

    protected $fillable = [
        'router_lanip',
        'router_wanip',
        'username',
        'password',
        'first_name',
        'last_name',
        'email',
        'phone',
        'address'
    ];

    protected $appends = [
        'full_name'
    ];

    // RELATIONSHIPS
    public function router_lanip(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(RouterLanip::class);
    }
    public function router_wanip(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(RouterWanip::class);
    }

    // SETTERS
    public function setFirstNameAttribute($value)
    {
        $this->attributes['first_name'] = \Str::ucfirst($value);
    }
    public function setLastNameAttribute($value)
    {
        $this->attributes['last_name'] = \Str::upper($value);
    }

    // GETTERS
    public function getFullNameAttribute()
    {
        return $this->first_name.' '.$this->last_name;
    }
}
