<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RouterWanip extends Model
{

    protected $fillable = [
        'router_id', 'ip_address', 'name', 'port_min', 'port_max'
    ];

    // RELATIONSHIPS
    public function router(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Router::class);
    }
    public function account(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Router::class);
    }
}
