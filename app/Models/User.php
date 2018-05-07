<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use Notifiable, HasApiTokens;

    protected $fillable = [
        'name', 'email', 'password',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    public function emailSchedules()
    {
        return $this->hasMany(EmailSchedule::class);
    }

    public static function getIdsByTimezone(): array
    {
        $users = static::all();

        $timezones = $users->pluck('timezone')->unique()->sort();

        $userIdsByTimezone = [];

        foreach ($timezones as $timezone) {
            $userIdsByTimezone[$timezone] = $users->where('timezone', $timezone)->pluck('id')->all();
        }

        return $userIdsByTimezone;
    }
}
