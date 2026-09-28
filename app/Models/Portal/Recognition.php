<?php

namespace App\Models\Portal;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recognition extends Model
{
    use HasFactory;

    protected $table = 'portal_recognitions';

    protected $fillable = ['type', 'title', 'body', 'icon', 'sort_order', 'is_active'];

    protected $casts = ['is_active' => 'boolean', 'sort_order' => 'integer'];
}
