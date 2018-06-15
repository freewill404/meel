<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FormatRequest extends Model
{
    protected $guarded = [];

    protected $casts = [
        'user_id'            => 'integer',
        'has_notified_admin' => 'boolean',
    ];
}
