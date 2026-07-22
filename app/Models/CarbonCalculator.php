<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarbonCalculator extends Model
{
    use HasFactory;
     protected $fillable = [
        'waste_type',
        'co2_factor',
        'point_per_kg',
        'tree_factor',
    ];
}
