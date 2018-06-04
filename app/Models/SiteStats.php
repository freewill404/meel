<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteStats extends Model
{
    protected $guarded = [];

    public $timestamps = false;

    protected $casts = [
        'users_registered'  => 'integer',
        'schedules_created' => 'integer',
        'emails_sent'       => 'integer',
    ];

    public static function today()
    {
        return static::firstOrCreate([
            'date' => now()->format('Y-m-d'),
        ], [
            'users_registered'  => 0,
            'schedules_created' => 0,
            'emails_sent'       => 0,
        ]);
    }

    public static function incrementUsersRegistered()
    {
        static::today()->increment('users_registered');
    }

    public static function incrementSchedulesCreated()
    {
        static::today()->increment('schedules_created');
    }

    public static function incrementEmailsSent()
    {
        static::today()->increment('emails_sent');
    }
}
