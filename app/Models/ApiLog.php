<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApiLog extends Model
{
    //
    protected $fillable=[
        'service',
        'endpoint',
        'request_payload',
        'response_payload',
        'status_code',
        'success',
    ];

    protected $casts =[

        'request_payload' => 'array',
        'response_payload' => 'array',
        'success' => 'boolean',
    ];
}
