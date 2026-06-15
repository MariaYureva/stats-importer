<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'orders';
    protected $guarded = [];

    protected $casts = [
        'date'             => 'date:Y-m-d',
        'last_change_date' => 'datetime:Y-m-d H:i:s',
        'cancel_dt'        => 'datetime:Y-m-d H:i:s',
        'total_price'      => 'decimal:2',
        'discount_percent' => 'integer',
        'income_id'        => 'integer',
        'odid'             => 'integer',
        'nm_id'            => 'integer',
        'is_cancel'        => 'boolean',
    ];
}
