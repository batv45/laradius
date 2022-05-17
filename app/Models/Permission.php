<?php

namespace App\Models;

use Rennokki\QueryCache\Traits\QueryCacheable;

class Permission extends \Spatie\Permission\Models\Permission
{
    use QueryCacheable;
}
