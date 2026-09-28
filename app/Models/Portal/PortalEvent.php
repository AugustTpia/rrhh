<?php

namespace App\Models\Portal;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PortalEvent extends Model
{
    use HasFactory;

    protected $table = 'portal_events';

    protected $fillable = ['title', 'description', 'type', 'event_date', 'sort_order', 'is_active'];

    protected $casts = ['event_date' => 'date', 'is_active' => 'boolean', 'sort_order' => 'integer'];
}
