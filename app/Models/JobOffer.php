<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobOffer extends Model
{
    protected $fillable = [
        'candidate_id',
        'vacancy_id',
        'status',
        'offered_at',
        'responded_at',
    ];

    protected $casts = [
        'offered_at' => 'datetime',
        'responded_at' => 'datetime',
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
