<?php

namespace App\Models;

use App\Events\EmailNotSent;
use App\Events\EmailSent;
use App\Events\ScheduledEmailNotSent;
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
        'email_confirmed'   => 'bool',
        'emails_left'       => 'integer',
        'emails_sent'       => 'integer',
        'emails_not_sent'   => 'integer',
        'schedules_created' => 'integer',
        'feeds_created'     => 'integer',
    ];

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    public function feeds()
    {
        return $this->hasMany(Feed::class);
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
