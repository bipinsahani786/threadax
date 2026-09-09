<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Blog extends Model
{
    protected $fillable = [
        'title', 'slug', 'excerpt', 'body', 'featured_image',
        'author', 'category', 'tags', 'status', 'published_at',
        'views', 'meta_title', 'meta_description',
    ];

    protected $casts = [
        'tags' => 'array',
        'published_at' => 'datetime',
    ];

    // Auto-generate slug
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($blog) {
            if (empty($blog->slug)) {
                $blog->slug = Str::slug($blog->title);
            }
        });
    }

    // Scopes
    public function scopePublished($query)
    {
        return $query->where('status', 'published')->whereNotNull('published_at');
    }

    public function scopeLatestFirst($query)
    {
        return $query->orderByDesc('published_at');
    }

    // Accessors
    public function getReadTimeAttribute()
    {
        $wordCount = str_word_count(strip_tags($this->body));
        return max(1, ceil($wordCount / 200));
    }

    public function getFeaturedImageUrlAttribute()
    {
        if (empty($this->featured_image)) {
            return null;
        }

        if (str_starts_with($this->featured_image, 'http://') || str_starts_with($this->featured_image, 'https://')) {
            return $this->featured_image;
        }

        $path = ltrim($this->featured_image, '/');
        while (str_starts_with($path, 'storage/')) {
            $path = ltrim(substr($path, 8), '/');
        }

        return asset('storage/' . $path);
    }
}
