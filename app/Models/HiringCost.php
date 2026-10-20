<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HiringCost extends Model
{
    protected $fillable = [
        'period',
        'total_investment',
        'hires_count',
    ];

    protected $casts = [
        'total_investment' => 'decimal:2',
        'hires_count' => 'integer',
    ];
}
