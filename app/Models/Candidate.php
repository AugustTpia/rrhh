<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Candidate extends Model
{
    use HasFactory;

    protected $fillable = [
        'vacancy_id',
        'full_name',
        'phone',
        'email',
        'source',
        'salary_expectation',
        'stage',
        'notes',
    ];

    public function vacancy()
    {
        return $this->belongsTo(Vacancy::class);
    }
}
