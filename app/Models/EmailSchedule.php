<?php

namespace App\Models;

use App\Jobs\SendScheduledEmailJob;
use App\Meel\EmailScheduleFormat;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class EmailSchedule extends Model
{
    protected $guarded = [];

    protected $casts = [
        'previous_occurrence' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function sendEmail()
    {
        SendScheduledEmailJob::dispatch($this);

        $this->emailScheduleHistories()->create([
            'sent_at'             => $this->next_occurrence,
            'sent_at_server_time' => now(),
        ]);

        $schedule = new EmailScheduleFormat($this->when);

        $this->update([
            'next_occurrence' => $schedule->isRecurring() ? $schedule->nextOccurrence() : null,
        ]);
    }

    public function getIsRecurringAttribute()
    {
        return (new EmailScheduleFormat($this->when))->isRecurring();
    }

    public function getLastSentAtAttribute()
    {
        return optional($this->emailScheduleHistories->first())->sent_at;
    }

    public function emailScheduleHistories()
    {
        return $this->hasMany(EmailScheduleHistory::class)->orderByDesc('sent_at');
    }

    public static function shouldBeSentNow(): Collection
    {
        $emailSchedules = collect();

        foreach (User::getIdsByTimezone() as $timezone => $userIds) {
            $timezoneNow = now($timezone)->second(0);

            $emailSchedules = EmailSchedule::query()
                ->whereIn('user_id', $userIds) // Only query EmailSchedules of users that are in this "timezone"
                ->where('next_occurrence', $timezoneNow)
                ->get()
                ->merge($emailSchedules);
        }

        return $emailSchedules->sortBy(function ($item) {
            return $item->id;
        })->values();
    }
}
