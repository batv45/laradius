<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{

    use HasFactory;

    protected $fillable = [
        'router_id',
        'username',
        'password',
        'first_name',
        'last_name',
        'email',
        'phone',
        'address',
        'lan_ip',
        'wan_ip',
        'wan_port_min',
        'wan_port_max'
    ];

    protected $appends = [
        'full_name'
    ];

    // RELATIONSHIPS
    public function router()
    {
        return $this->belongsTo(Router::class);
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
