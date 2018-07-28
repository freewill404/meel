<?php

namespace App\Models;

use App\Events\Feeds\FeedCreating;
use App\Meel\Schedules\ScheduleFormat;
use App\Support\DateTime\SecondlessDateTimeString;
use Illuminate\Database\Eloquent\Model;
use RuntimeException;

class Feed extends Model
{
    protected $guarded = [];

    protected $dispatchesEvents = [
        'creating' => FeedCreating::class,
    ];

    protected $casts = [
        'user_id'           => 'integer',
        'emails_sent'       => 'integer',
        'next_poll_at'      => 'datetime',
        'last_polled_at'    => 'datetime',
        'group_new_entries' => 'boolean',
        'last_poll_failed'  => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getNextPollDate(): SecondlessDateTimeString
    {
        if ($this->when === null) {
            return secondless_now()->addMinutes(15);
        }

        $schedule = new ScheduleFormat($this->when, $this->user->timezone);

        if (! $schedule->isRecurring()) {
            throw new RuntimeException('Feed '.$this->id.' has a schedule that is not recurring: '.$this->when);
        }

        return $schedule->nextOccurrence()->changeTimezone($this->user->timezone, 'Europe/Amsterdam');
    }

    public function scopeShouldBePolledNow($query)
    {
        return $query->where('next_poll_at', '<=', secondless_now());
    }
}
