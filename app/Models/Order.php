<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'facility_id',
        'customer_name',
        'customer_phone',
        'customer_email',
        'delivery_address',
        'payment_method',
        'special_instructions',
        'items',
        'total',
        'order_date',
    ];

    protected $casts = [
        'items' => 'array',
        'order_date' => 'datetime',
    ];
}
