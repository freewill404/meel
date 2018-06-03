<?php

namespace App\Jobs;

use App\Events\EmailSent;
use App\Mail\Email;
use App\Models\EmailSchedule;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class SendScheduledEmailJob extends BaseJob implements ShouldQueue
{
    protected $schedule;

    public function __construct(EmailSchedule $schedule)
    {
        $this->schedule = $schedule;
    }

    public function handle()
    {
        $email = new Email($this->schedule);

        Mail::send($email);

        EmailSent::dispatch($this->schedule, $email);
    }
}
