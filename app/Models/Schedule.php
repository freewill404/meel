<?php

namespace App\Models;

use App\Events\ScheduledEmailNotSent;
use App\Events\ScheduledEmailSent;
use App\Mail\Email;
use App\Meel\Schedules\ScheduleFormat;
use App\Meel\Schedules\WhatString;
use Illuminate\Database\Eloquent\Model;
use Propaganistas\LaravelFakeId\RoutesWithFakeIds as ObfuscateRouteIds;

class Schedule extends Model
{
    use ObfuscateRouteIds;

    protected $guarded = [];

    protected $casts = [
        'user_id'         => 'integer',
        'times_sent'      => 'integer',
        'next_occurrence' => 'datetime',
        'last_sent_at'    => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function sendEmail()
    {
        $email = new Email($this);

        $this->user->sendEmail($email)
            ? ScheduledEmailSent::dispatch($this, $email)
            : ScheduledEmailNotSent::dispatch($this);
    }

    public function getIsRecurringAttribute()
    {
        $schedule = new ScheduleFormat($this->when);

        return $schedule->isRecurring();
    }

    public function getFormattedWhatAttribute()
    {
        return WhatString::format($this);
    }

    public function getObfuscatedIdAttribute()
    {
        return $this->getRouteKey();
    }

    public function scopeShouldBeSentNow($query)
    {
        return $query->where('next_occurrence', '<=', secondless_now());
    }
}
