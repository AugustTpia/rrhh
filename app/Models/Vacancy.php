<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vacancy extends Model
{
    use HasFactory;

    protected $fillable = [
        'folio',
        'title',
        'branch',
        'region',
        'reason',
        'type',
        'description',
        'status',
        'closed_at',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'closed_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
