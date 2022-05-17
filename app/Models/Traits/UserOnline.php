<?php


namespace App\Models\Traits;

use App\Models\User;
use HighIdeas\UsersOnline\Traits\UsersOnlineTrait;
use Spatie\Permission\Models\Role;

trait UserOnline
{
    use UsersOnlineTrait;
    public static function allAdminOnline()
    {
        return Role::findByName('admin')->users()->get()->filter->isOnline();
    }
    public static function allUserOnline()
    {
        $idler = \Spatie\Permission\Models\Role::findByName('admin')->users()->pluck('id');
        return User::whereNotIn('id',$idler)->get()->filter->isOnline();
    }
}
