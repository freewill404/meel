<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use Notifiable, HasApiTokens;

    protected $fillable = [
        'name', 'email', 'password', 'email_confirmed', 'email_confirm_token'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_confirmed' => 'bool',
    ];

    public function emailSchedules()
    {
        return $this->hasMany(EmailSchedule::class)->orderBy('next_occurrence');
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
