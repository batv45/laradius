<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Rennokki\QueryCache\Traits\QueryCacheable;

class Role extends \Spatie\Permission\Models\Role
{
    use QueryCacheable;
}
