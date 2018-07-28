<?php

namespace App\Listeners;

use App\Mail\ConfirmAccountEmail;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Mail;

class SendConfirmAccountEmail
{
    public function handle(Registered $event)
    {
        $email = new ConfirmAccountEmail($event->user);

        Mail::to($event->user)->queue($email);
    }
}
