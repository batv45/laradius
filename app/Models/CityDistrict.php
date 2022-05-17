<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Rennokki\QueryCache\Traits\QueryCacheable;

class CityDistrict extends Model
{
//    use QueryCacheable;

    public $timestamps = false;
    protected $table = 'geozone_city_districts';
    protected $fillable = ['city_id', 'ilce', 'semt', 'mahalle', 'posta_kodu'];

    public function city(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(City::class);
    }

}
