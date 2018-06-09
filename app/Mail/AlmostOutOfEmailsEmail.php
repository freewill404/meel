<?php

namespace App\Mail;

class AlmostOutOfEmailsEmail extends UserEmail
{
    public function build()
    {
        return $this->subject('You are almost out of emails | Meel.me')
                    ->view('email.almost-out-of-emails');
    }
}
