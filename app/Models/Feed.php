<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Feed extends Model
{
    protected $guarded = [];

    protected $casts = [
        'user_id'        => 'integer',
        'last_polled_at' => 'datetime',
        'emails_sent'    => 'integer',
    ];
}
