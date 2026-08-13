<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $fillable = ['user_id', 'name', 'phone', 'alternate_phone', 'line1', 'line2', 'landmark', 'city', 'state', 'pincode', 'type', 'is_default'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
