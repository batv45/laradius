<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RouterLanip extends Model
{

    protected $fillable = [
      'router_id', 'ip_address', 'name'
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
