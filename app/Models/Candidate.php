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

    public function stages()
    {
        return $this->hasMany(CandidateStage::class);
    }

    public function offers()
    {
        return $this->hasMany(JobOffer::class);
    }

    public function hire()
    {
        return $this->hasOne(Hire::class);
    }
}
