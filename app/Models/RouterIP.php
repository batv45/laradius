<?php

namespace App\Models;

use App\Enums\RouterIPType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use IPv4\SubnetCalculator;

class RouterIP extends Model
{
    private int $max_port = 65535;
    private int $min_port = 1000;
    protected $fillable = [
        'router_id', 'ip', 'subnet_mask', 'type', 'account_limit'
    ];
    protected $appends = [
        'type_text'
    ];

    protected $casts = [
        'type' => RouterIPType::class
    ];

    // RELATIONSHIPS
    public function router()
    {
        $this->belongsTo(Router::class);
    }

    // GETTERS
    public function getTypeTextAttribute()
    {
        return $this->type->description;
    }

    public function getUsableIpsAttribute()
    {
        return $this->getAllHostIPAddresses();
    }

    public function getPortRangeAttribute()
    {
        return $this->getPortRange();
    }

    public function getPortRange()
    {
        $ports = collect();
        if($this->account_limit < 1)
            return $ports;
        $range = ($this->max_port - ($this->min_port-1)) / $this->account_limit;
        $start = $this->min_port-1;

        for ($i = 1; $i < $this->account_limit+1; $i++){
            $ran = intval($start+$range);
            $ports->push([
               'min'=> $start+1,
                'max' => $ran
            ]);
            $start = $ran;
        }
        return $ports;
    }

    /**
     * @param array $used_ips
     * @return Collection
     */
    public function getAllHostIPAddresses(array $used_ips = []) : Collection
    {
        $sub = new SubnetCalculator($this->ip, $this->subnet_mask);
        $ip_collection = collect();
        foreach ($sub->getAllHostIPAddresses() as $ipAddress) {
            $ip_collection->push($ipAddress);
        }
        $filtered = $ip_collection->reject(function ($value, $key) use($used_ips) {
            return in_array($value,$used_ips);
        });

        return $filtered->collect();
    }

    public function isIPAddressInSubnet(string $ip) : bool
    {
        $sub = new SubnetCalculator($this->ip, $this->subnet_mask);
        return $sub->isIPAddressInSubnet($ip);
    }

}
