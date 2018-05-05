<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailSchedule extends Model
{
    protected $guarded = [];

    protected $casts = [
        'previous_occurrence' => 'datetime',
        'next_occurrence'     => 'datetime',
        'times_sent'          => 'integer',
        'disabled'            => 'bool',
    ];
}
