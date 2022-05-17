<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentHistory extends Model
{

    protected $fillable = [
        'user_id',
        'price',
        'description',
        'result_payment_id',
        'raw_result',
        'paymented_at'
    ];

    protected $casts = [
        'user_id' => 'int',
        'price' => 'float',
        'raw_result' => 'json',
        'paymented_at' => 'datetime'
    ];

    //region Relationships
    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    //endregion
}
