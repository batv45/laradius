<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Router extends Model
{
    use SoftDeletes;

    protected $fillable = [
      'ip', 'port', 'username', 'password', 'description', 'identity'
    ];

    // RELATIONSHIPS

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne|Account
     */
    public function account()
    {
        return $this->hasOne(Account::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany|RouterIP
     */
    public function ips()
    {
        return $this->hasMany(RouterIP::class);
    }

    // GETTERS


    /**
     * Set and fetch RouterOS Client
     *
     * @return \RouterOS\Interfaces\ClientInterface
     * @throws \RouterOS\Exceptions\ClientException
     * @throws \RouterOS\Exceptions\ConfigException
     * @throws \RouterOS\Exceptions\QueryException
     */
    public function getRouterOS(int $timeout = 5)
    {
        return \RouterOS::client([
            'host' => $this->ip,
            'user' => $this->username,
            'pass' => $this->password,
            'port' => $this->port,
            'timeout' => $timeout,
        ]);
    }

    public function updateIdentity()
    {
        $identity = collect(array_values($this->getRouterOS()->query('/system/identity/print')->read())[0]);
        if($identity->has('name')){
            $this->update(['identity' => $identity->get('name')]);
        }
    }

    public function getClientStatusAttribute() : bool
    {
        try {
            $this->getRouterOS(1);
            return true;
        }catch (\Exception $e){
            return false;
        }
    }
    public function getAccountActiveAttribute() : int
    {
        try {
            return collect($this->getRouterOS(1)->query('/ppp/active/print')->read())->count();
        }catch (\Exception $e){
            return 0;
        }
    }
}
