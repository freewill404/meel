<?php

namespace App\Models;

use App\Events\EmailNotSent;
use App\Jobs\SendScheduledEmailJob;
use App\Meel\Schedules\ScheduleFormat;
use App\Meel\Schedules\WhatString;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class Schedule extends Model
{
    protected $guarded = [];

    protected $casts = [
        'user_id' => 'integer',
    ];

    protected $with = [
        'scheduleHistories',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scheduleHistories()
    {
        return $this->hasMany(ScheduleHistory::class)->orderByDesc('sent_at');
    }

    public function sendEmail()
    {
        $this->user->emails_left
            ? SendScheduledEmailJob::dispatch($this)
            : EmailNotSent::dispatch($this);
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

    public function getTimesSentAttribute()
    {
        return $this->scheduleHistories->count();
    }

    public function getLastSentAtAttribute()
    {
        $history = $this->scheduleHistories->first();

        return $history ? $history->sent_at : null;
    }

    public static function shouldBeSentNow(): Collection
    {
        $schedules = collect();

        foreach (User::getIdsByTimezone() as $timezone => $userIds) {
            $timezoneNow = secondless_now($timezone);

            $schedules = Schedule::query()
                ->whereIn('user_id', $userIds)
                ->where('next_occurrence', '<=', $timezoneNow)
                ->get()
                ->merge($schedules);
        }

        return $schedules->sortBy(function ($item) {
            return $item->id;
        })->values();
    }
}
