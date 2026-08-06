<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Machine extends Model
{
    use HasFactory;

    protected $fillable = [
        'device_id',
        'name',
        'latitude',
        'longitude',
        'location_name',
        'status',
        'satellites',
        'signal_strength',
        'last_seen',
    ];

    protected $casts = [
        'latitude' => 'float',
        'longitude' => 'float',
        'last_seen' => 'datetime',
    ];
}
