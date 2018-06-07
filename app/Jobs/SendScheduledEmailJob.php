<?php

namespace App\Jobs;

use App\Events\EmailSent;
use App\Mail\Email;
use App\Models\EmailSchedule;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class SendScheduledEmailJob extends BaseJob implements ShouldQueue
{
    public $emailSchedule;

    public function __construct(EmailSchedule $emailSchedule)
    {
        $this->emailSchedule = $emailSchedule;
    }

    public function handle()
    {
        Mail::to($this->emailSchedule->user)->send(
            $email = new Email($this->emailSchedule)
        );

        EmailSent::dispatch($this->emailSchedule, $email);
    }
}
