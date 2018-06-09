<?php

namespace App\Listeners;

use App\Events\UserAlmostOutOfEmails;
use App\Mail\AlmostOutOfEmailsEmail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class SendAlmostOutOfEmailsEmail implements ShouldQueue
{
    public function handle(UserAlmostOutOfEmails $event)
    {
        Mail::to($event->user)->send(
            new AlmostOutOfEmailsEmail($event->user)
        );
    }
}
