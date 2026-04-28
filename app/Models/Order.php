<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    //
    protected $fillable =[
        'customer_id',
        'status',
        'total_amount',
        'currency',
        'notes',
    ];
    public function customer()
    {
        return $this->belongsTo(Customer::class);

    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);

    }

    public function shipment()
    {
        return $this->hasOne(Shipment::class);

    }
}
