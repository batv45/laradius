<?php

namespace App\Models;

use App\Models\Traits\UserModelTrait;
use App\Models\Traits\UserOnline;
use Batv45\Balance\HasBalance;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use App\Models\Traits\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use Rennokki\QueryCache\Traits\QueryCacheable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    use UserModelTrait;

    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;
    use SoftDeletes;
    use UserOnline;
    use HasRoles;
    use QueryCacheable;
    use HasBalance;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password','settings',
        'tc_kn',
        'birth_year',
        'tc_verified_at',
        'last_login_at',
        'last_login_ip_address'

    ];
    protected $attributes = [
        'settings' => '[]',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'tc_verified_at' => 'datetime',
        'last_login_at' => 'datetime',
        'settings' => 'json',
        'balance' => 'float'
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'profile_photo_url',
        'full_name',
        'is_online',
        'balance_tl'
    ];

}
