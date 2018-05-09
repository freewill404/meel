<?php

namespace App\Models;

use App\Mail\Email;
use App\Meel\EmailScheduleFormat;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Mail;
use RuntimeException;

class EmailSchedule extends Model
{
    protected $guarded = [];

    protected $casts = [
        'previous_occurrence' => 'datetime',
        'disabled'            => 'bool',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function sendEmail()
    {
        Mail::send(
            new Email($this)
        );

        $this->emailScheduleHistories()->create([
            'sent_at' => $this->next_occurrence,
        ]);

        $schedule = new EmailScheduleFormat($this->when);

        $this->update([
            'next_occurrence' => $schedule->isRecurring() ? $schedule->nextOccurrence() : null,
        ]);
    }

    public function emailScheduleHistories()
    {
        return $this->hasMany(EmailScheduleHistory::class);
    }

    public static function shouldBeSentNow(): Collection
    {
        $emailSchedules = collect();

        foreach (User::getIdsByTimezone() as $timezone => $userIds) {
            $timezoneNow = now($timezone)->second(0);

            $emailSchedules = EmailSchedule::query()
                ->whereIn('user_id', $userIds) // Only query EmailSchedules of users that are in this "timezone"
                ->where('next_occurrence', $timezoneNow)
                ->where('disabled', false)
                ->get()
                ->merge($emailSchedules);
        }

        return $emailSchedules->sortBy(function ($item) {
            return $item->id;
        })->values();
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function (EmailSchedule $emailSchedule) {
            $schedule = new EmailScheduleFormat($emailSchedule->when, $emailSchedule->user->timezone);

            $nextOccurrence = $schedule->nextOccurrence();

            if ($nextOccurrence === null) {
                throw new RuntimeException('Tried creating an EmailSchedule with an invalid "when": '.$emailSchedule->when);
            }

            $emailSchedule->next_occurrence = $nextOccurrence;
        });
    }
}