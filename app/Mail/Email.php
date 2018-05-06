<?php

namespace App\Mail;

use App\Models\EmailSchedule;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Email extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(EmailSchedule $emailSchedule)
    {
        $this->to($emailSchedule->user->email)
            ->subject($emailSchedule->what.' | Meel.me');
    }

    public function build()
    {
        return $this->view('email.email');
    }
}
