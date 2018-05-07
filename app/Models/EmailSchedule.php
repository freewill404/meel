<?php

namespace App\Models;

use App\Mail\Email;
use App\Meel\EmailScheduleFormat;
use Doctrine\DBAL\Query\QueryBuilder;
use Illuminate\Database\Eloquent\Model;
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
