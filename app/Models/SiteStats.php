<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteStats extends Model
{
    protected $guarded = [];

    public $timestamps = false;

    protected $casts = [
        'users_registered'          => 'integer',
        'email_schedules_created'   => 'integer',
        'scheduled_emails_sent'     => 'integer',
        'scheduled_emails_not_sent' => 'integer',
        'feeds_created'             => 'integer',
        'feed_emails_sent'          => 'integer',
        'feed_emails_not_sent'      => 'integer',
        'feed_polls'                => 'integer',
    ];

    public static function today()
    {
        return static::firstOrCreate([
            'date' => now()->format('Y-m-d'),
        ], [
            'users_registered'          => 0,
            'email_schedules_created'   => 0,
            'scheduled_emails_sent'     => 0,
            'scheduled_emails_not_sent' => 0,
            'feeds_created'             => 0,
            'feed_emails_sent'          => 0,
            'feed_emails_not_sent'      => 0,
            'feed_polls'                => 0,
        ]);
    }

    public function getEmailsSentAttribute()
    {
        return $this->scheduled_emails_sent + $this->feed_emails_sent;
    }

    public function getEmailsNotSentAttribute()
    {
        return $this->scheduled_emails_not_sent + $this->feed_emails_not_sent;
    }

    public static function incrementUsersRegistered()
    {
        static::today()->increment('users_registered');
    }

    public static function incrementEmailSchedulesCreated()
    {
        static::today()->increment('email_schedules_created');
    }

    public static function incrementScheduledEmailsSent()
    {
        static::today()->increment('scheduled_emails_sent');
    }

    public static function incrementScheduledEmailsNotSent()
    {
        static::today()->increment('scheduled_emails_not_sent');
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

    public static function incrementFeedEmailsNotSent()
    {
        static::today()->increment('feed_emails_not_sent');
    }
}
