<?php

namespace App\Jobs;

use App\Events\EmailSent;
use App\Mail\Email;
use App\Models\Schedule;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class SendScheduledEmailJob extends BaseJob implements ShouldQueue
{
    public $schedule;

    public function __construct(Schedule $schedule)
    {
        $this->schedule = $schedule;
    }

    public function handle()
    {
        Mail::to($this->schedule->user)->send(
            $email = new Email($this->schedule)
        );

        EmailSent::dispatch($this->schedule, $email);
    }
}
