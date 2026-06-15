<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Income extends Model
{
    protected $table = 'incomes';
    protected $guarded = [];

    protected $casts = [
        'date'             => 'date:Y-m-d',
        'date_close'       => 'date:Y-m-d',
        'last_change_date' => 'datetime:Y-m-d H:i:s',
        'quantity'         => 'integer',
        'total_price'      => 'decimal:2',
        'income_id'        => 'integer',
        'nm_id'            => 'integer',
    ];
}
