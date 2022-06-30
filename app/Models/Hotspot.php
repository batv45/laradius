<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hotspot extends Model
{
    protected $fillable = [
      'router_id',
        'name',
        'description',
        'link_login_post'
    ];
}
