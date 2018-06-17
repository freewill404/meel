<?php

namespace App\Models;

use App\Events\EmailNotSent;
use App\Jobs\SendScheduledEmailJob;
use App\Meel\EmailScheduleFormat;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class EmailSchedule extends Model
{
    protected $guarded = [];

    protected $casts = [
        'user_id'             => 'integer',
        'previous_occurrence' => 'datetime',
    ];

    protected $with = [
        'emailScheduleHistories',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function sendEmail()
    {
        $this->user->emails_left
            ? SendScheduledEmailJob::dispatch($this)
            : EmailNotSent::dispatch($this);
    }

    public function getIsRecurringAttribute()
    {
        $schedule = new EmailScheduleFormat($this->when);

        return $schedule->isRecurring();
    }

    public function getTimesSentAttribute()
    {
        return $this->emailScheduleHistories->count();
    }

    public function getLastSentAtAttribute()
    {
        $history = $this->emailScheduleHistories->first();

        return $history ? $history->sent_at : null;
    }

    public function emailScheduleHistories()
    {
        return $this->hasMany(EmailScheduleHistory::class)->orderByDesc('sent_at');
    }

    public static function shouldBeSentNow(): Collection
    {
        $emailSchedules = collect();

        foreach (User::getIdsByTimezone() as $timezone => $userIds) {
            $timezoneNow = secondless_now($timezone);

            $emailSchedules = EmailSchedule::query()
                ->whereIn('user_id', $userIds)
                ->where('next_occurrence', '<=', $timezoneNow)
                ->get()
                ->merge($emailSchedules);
        }

        return $emailSchedules->sortBy(function ($item) {
            return $item->id;
        })->values();
    }
}
