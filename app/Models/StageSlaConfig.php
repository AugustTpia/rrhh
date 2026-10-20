<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StageSlaConfig extends Model
{
    protected $fillable = [
        'stage',
        'sla_days',
    ];

    protected $casts = [
        'sla_days' => 'integer',
    ];
}
