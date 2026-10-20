<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CandidateStage extends Model
{
    protected $fillable = [
        'candidate_id',
        'vacancy_id',
        'stage',
        'completed_at',
    ];

    protected $casts = [
        'completed_at' => 'datetime',
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
