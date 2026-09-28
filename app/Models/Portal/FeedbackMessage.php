<?php

namespace App\Models\Portal;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeedbackMessage extends Model
{
    use HasFactory;

    protected $fillable = ['body', 'is_anonymous', 'is_read', 'is_archived'];

    protected $casts = ['is_anonymous' => 'boolean', 'is_read' => 'boolean', 'is_archived' => 'boolean'];
}
