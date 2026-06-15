<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    protected $table = 'stocks';
    protected $guarded = [];

    protected $casts = [
        'date'               => 'date:Y-m-d',
        'last_change_date'   => 'datetime:Y-m-d H:i:s',
        'quantity'           => 'integer',
        'quantity_full'      => 'integer',
        'in_way_to_client'   => 'integer',
        'in_way_from_client' => 'integer',
        'is_supply'          => 'boolean',
        'is_realization'     => 'boolean',
        'nm_id'              => 'integer',
        'price'              => 'decimal:2',
        'discount'           => 'decimal:2',
    ];
}
