<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;

    protected $guarded = [];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_confirmed'   => 'bool',
        'free_emails_left'  => 'integer',
        'paid_emails_left'  => 'integer',
        'emails_sent'       => 'integer',
        'emails_not_sent'   => 'integer',
        'schedules_created' => 'integer',
    ];

    public function emailSchedules()
    {
        return $this->hasMany(EmailSchedule::class)->orderBy('next_occurrence');
    }

    public function getDefaultWhenAttribute()
    {
        return 'now';
    }

    public function getHasEmailsLeftAttribute()
    {
        return $this->free_emails_left > 0 || $this->paid_emails_left > 0;
    }

    public static function getIdsByTimezone(): array
    {
        return static::query()
            ->select('id', 'timezone')
            ->get()
            ->mapToGroups(function ($user) {
                return [$user->timezone => $user->id];
            })
            ->sortKeys()
            ->toArray();
    }
}
