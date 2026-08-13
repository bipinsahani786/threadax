<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    protected $fillable = ['title', 'slug', 'body', 'meta_title', 'meta_description', 'is_active'];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
