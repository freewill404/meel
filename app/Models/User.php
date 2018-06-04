<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
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
        'emails_sent'       => 'integer',
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
