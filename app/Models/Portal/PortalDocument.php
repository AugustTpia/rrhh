<?php

namespace App\Models\Portal;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class PortalDocument extends Model
{
    use HasFactory;

    protected $table = 'portal_documents';

    protected $fillable = ['name', 'file_path', 'external_url', 'sort_order', 'is_active'];

    protected $casts = ['is_active' => 'boolean', 'sort_order' => 'integer'];

    public function downloadUrl(): string
    {
        if ($this->file_path) {
            return Storage::disk('public')->url($this->file_path);
        }

        return $this->external_url ?: '#';
    }
}
