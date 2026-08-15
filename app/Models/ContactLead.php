<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactLead extends Model
{
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'order_number',
        'message',
        'status',
        'follow_up_date',
        'notes',
    ];

    protected $casts = [
        'follow_up_date' => 'date',
    ];
}
