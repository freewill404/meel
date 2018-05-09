<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailScheduleHistory extends Model
{
    protected $guarded = [];

    protected $casts = [
        'sent_at' => 'datetime',
    ];
}