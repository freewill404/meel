<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
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
    ];

    public function schedules()
    {
        return $this->hasMany(Schedule::class)->orderBy('next_occurrence');
    }

    public function feeds()
    {
        return $this->hasMany(Feed::class);
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
