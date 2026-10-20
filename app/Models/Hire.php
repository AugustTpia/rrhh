<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hire extends Model
{
    protected $fillable = [
        'candidate_id',
        'vacancy_id',
        'hire_date',
        'termination_date',
    ];

    protected $casts = [
        'hire_date' => 'date',
        'termination_date' => 'date',
    ];

    public function candidate()
    {
        return $this->belongsTo(Candidate::class);
    }

    public function vacancy()
    {
        return $this->belongsTo(Vacancy::class);
    }
}
