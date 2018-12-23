<?php

namespace App\Models;

use App\Events\Feeds\FeedCreating;
use App\Meel\When\ScheduleFormat;
use App\Support\DateTime\SecondlessDateTimeString;
use Illuminate\Database\Eloquent\Model;
use Propaganistas\LaravelFakeId\RoutesWithFakeIds as ObfuscateRouteIds;
use RuntimeException;

class Feed extends Model
{
    use ObfuscateRouteIds;

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

        $schedule = new ScheduleFormat(
            now($this->user->timezone),
            $this->when
        );

        if (! $schedule->recurring()) {
            throw new RuntimeException('Feed '.$this->id.' has a schedule that is not recurring: '.$this->when);
        }

        return $schedule->nextOccurrence()->changeTimezone($this->user->timezone, 'Europe/Amsterdam');
    }

    public function scopeShouldBePolledNow($query)
    {
        return $query->where('next_poll_at', '<=', secondless_now());
    }
}
