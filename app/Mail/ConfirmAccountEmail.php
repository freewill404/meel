<?php

namespace App\Mail;

class ConfirmAccountEmail extends UserEmail
{
    public function build()
    {
        return $this->subject('Confirm your account | Meel.me')
                    ->view('email.confirm-account');
    }
}
