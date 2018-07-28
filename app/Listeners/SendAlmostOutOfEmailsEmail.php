<?php

namespace App\Listeners;

use App\Events\UserAlmostOutOfEmails;
use App\Mail\AlmostOutOfEmailsEmail;
use Illuminate\Support\Facades\Mail;

class SendAlmostOutOfEmailsEmail
{
    public function handle(UserAlmostOutOfEmails $event)
    {
        $email = new AlmostOutOfEmailsEmail($event->user);

        Mail::to($event->user)->queue($email);
    }
}
