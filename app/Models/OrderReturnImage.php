<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class OrderReturnImage extends Model
{
    protected $fillable = [
        'order_return_id',
        'image_path',
    ];

    public function returnRequest()
    {
        return $this->belongsTo(OrderReturn::class, 'order_return_id');
    }

    public function getUrlAttribute(): string
    {
        if (str_starts_with($this->image_path, 'http')) {
            return $this->image_path;
        }

        return Storage::disk('public')->url($this->image_path);
    }
}
