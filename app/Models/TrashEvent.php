<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrashEvent extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'jenis_sampah',
        'poin',
        'nilai_rp',
        'sensor_proximity',
        'sensor_ultrasonic'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}