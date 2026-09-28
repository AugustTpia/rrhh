<?php

namespace App\Models\Portal;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class NewsItem extends Model
{
    use HasFactory;

    protected $table = 'portal_news';

    protected $fillable = ['title', 'excerpt', 'category', 'author', 'published_at', 'image_url', 'image_path', 'is_featured', 'is_published'];

    protected $casts = ['published_at' => 'datetime', 'is_featured' => 'boolean', 'is_published' => 'boolean'];

    public function imageSrc(): ?string
    {
        if ($this->image_path) {
            return Storage::disk('public')->url($this->image_path);
        }

        return $this->image_url;
    }
}
