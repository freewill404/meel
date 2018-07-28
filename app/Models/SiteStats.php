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
        'emails_not_sent'   => 'integer',
        'feeds_created'     => 'integer',
        'feed_polls'        => 'integer',
        'feed_emails_sent'  => 'integer',
    ];

    public static function today()
    {
        return static::firstOrCreate([
            'date' => now()->format('Y-m-d'),
        ], [
            'users_registered'  => 0,
            'schedules_created' => 0,
            'emails_sent'       => 0,
            'emails_not_sent'   => 0,
            'feeds_created'     => 0,
            'feed_polls'        => 0,
            'feed_emails_sent'  => 0,
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

    public static function incrementEmailsNotSent()
    {
        static::today()->increment('emails_not_sent');
    }

    public static function incrementFeedsCreated()
    {
        static::today()->increment('feeds_created');
    }

    public static function incrementFeedPolls()
    {
        static::today()->increment('feed_polls');
    }

    public static function incrementFeedEmailsSent()
    {
        static::today()->increment('feed_emails_sent');
    }
}
