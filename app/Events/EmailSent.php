<?php

namespace App\Events;

use App\Mail\Email;
use App\Models\EmailSchedule;

class EmailSent extends BaseEvent
{
    public $emailSchedule;

    public $email;

    public function __construct(EmailSchedule $emailSchedule, Email $email)
    {
        $this->emailSchedule = $emailSchedule;

        $this->email = $email;
    }
}
