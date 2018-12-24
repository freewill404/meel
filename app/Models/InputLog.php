<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InputLog extends Model
{
    protected $guarded = [];

    public $timestamps = false;

    protected $casts = [
        'usable' => 'bool',
        'recurring' => 'bool',
    ];
}
