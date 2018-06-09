<?php

namespace App\Listeners;

use App\Events\UserOutOfEmails;
use App\Mail\OutOfEmailsEmail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class SendOutOfEmailsEmail implements ShouldQueue
{
    public function handle(UserOutOfEmails $event)
    {
        Mail::to($event->user)->send(
            new OutOfEmailsEmail($event->user)
        );
    }
}
