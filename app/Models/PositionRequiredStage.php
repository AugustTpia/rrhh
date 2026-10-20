<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PositionRequiredStage extends Model
{
    protected $fillable = [
        'position_title',
        'stage',
    ];
}
