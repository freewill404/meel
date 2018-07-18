<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScheduleHistory extends Model
{
    protected $guarded = [];

    public $timestamps = false;

    protected $casts = [
        'sent_at' => 'datetime',
    ];
}
