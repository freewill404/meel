<?php

namespace App\Mail;

use App\Models\EmailSchedule;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Email extends Mailable
{
    use Queueable, SerializesModels;

    protected $emailSchedule;

    public function __construct(EmailSchedule $emailSchedule)
    {
        $this->emailSchedule = $emailSchedule;

        $this->to($emailSchedule->user->email)->subject($emailSchedule->what);
    }

    public function build()
    {
        return $this->view('email.email', [
            'emailSchedule' => $this->emailSchedule,
        ]);
    }
}
