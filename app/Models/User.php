<?php

namespace App\Models;

use App\Events\EmailNotSent;
use App\Events\EmailSent;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Mail\Mailable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Mail;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use Notifiable, HasApiTokens;

    protected $guarded = [];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_confirmed'           => 'bool',
        'emails_left'               => 'integer',
        'max_feeds'                 => 'integer',
        'email_schedules_created'   => 'integer',
        'scheduled_emails_sent'     => 'integer',
        'scheduled_emails_not_sent' => 'integer',
        'feeds_created'             => 'integer',
        'feed_emails_sent'          => 'integer',
        'feed_emails_not_sent'      => 'integer',
    ];

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    public function feeds()
    {
        return $this->hasMany(Feed::class);
    }

    public function getEmailsSentAttribute()
    {
        return $this->scheduled_emails_sent + $this->feed_emails_sent;
    }

    public function getEmailsNotSentAttribute()
    {
        return $this->scheduled_emails_not_sent + $this->feed_emails_not_sent;
    }

    public function getHasFeedsLeftAttribute()
    {
        return $this->feeds()->count() < $this->max_feeds;
    }

    public function getDefaultWhenAttribute()
    {
        return 'now';
    }

    public function sendEmail(Mailable $email)
    {
        if (! $this->emails_left) {
            EmailNotSent::dispatch($this, $email);

            return false;
        }

        Mail::to($this)->queue($email);

        EmailSent::dispatch($this, $email);

        return true;
    }
}
