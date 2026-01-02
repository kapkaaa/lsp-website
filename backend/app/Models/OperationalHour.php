<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OperationalHour extends Model
{
    protected $fillable = [
        'day',
        'opening_time',
        'closing_time',
        'status'
    ];

    protected $casts = [
        'opening_time' => 'datetime:H:i',
        'closing_time' => 'datetime:H:i',
    ];
}
