<?php

namespace App\Mail;

use App\Models\EmailSchedule;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Email extends Mailable
{
    use Queueable, SerializesModels;

    public $emailSchedule;

    public function __construct(EmailSchedule $emailSchedule)
    {
        $this->emailSchedule = $emailSchedule;
    }

    public function build()
    {
        return $this->subject($this->emailSchedule->what)
                    ->view('email.email');
    }
}
