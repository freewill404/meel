<?php

namespace App\Listeners;

use App\Events\UserOutOfEmails;
use App\Mail\OutOfEmailsEmail;
use Illuminate\Support\Facades\Mail;

class SendOutOfEmailsEmail
{
    public function handle(UserOutOfEmails $event)
    {
        $email = new OutOfEmailsEmail($event->user);

        Mail::to($event->user)->queue($email);
    }
}
