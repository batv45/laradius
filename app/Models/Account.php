<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{

    use HasFactory;

    protected $fillable = [
        'router_lanip_id',
        'router_wanip_id',
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
    public function router_lanip(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(RouterLanip::class);
    }
    public function router_wanip(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(RouterWanip::class);
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

    public function getRouterIdAttribute()
    {
        return $this->router_lanip->router_id;
    }
    public function getWanIPAddressAttribute()
    {
        return $this->router_wanip->ip_address;
    }
}
