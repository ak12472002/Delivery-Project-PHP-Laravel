<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shipment extends Model
{
    //
    protected $fillable =[
        'order_id',
        'provider',
        'tracking_number',
        'status',
        'quoted_price',
        'currency',
        'priority',
        'provider_response',
    ];

    protected $casts =[
        'provider_response' => 'array',
        'priority' => 'boolean',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
