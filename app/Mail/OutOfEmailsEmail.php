<?php

namespace App\Mail;

class OutOfEmailsEmail extends UserEmail
{
    public function build()
    {
        return $this->subject('You are out of emails! | Meel.me')
                    ->view('email.out-of-emails');
    }
}
