<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $table = 'sales';
    protected $guarded = [];

    protected $casts = [
        'date'                => 'date:Y-m-d',
        'last_change_date'    => 'datetime:Y-m-d H:i:s',
        'total_price'         => 'decimal:2',
        'discount_percent'    => 'integer',
        'is_supply'           => 'boolean',
        'is_realization'      => 'boolean',
        'promo_code_discount' => 'decimal:2',
        'income_id'           => 'integer',
        'odid'                => 'integer',
        'spp'                 => 'decimal:2',
        'for_pay'             => 'decimal:2',
        'finished_price'      => 'decimal:2',
        'price_with_disc'     => 'decimal:2',
        'nm_id'               => 'integer',
    ];
}
