<?php

namespace App\Listeners;

use App\Mail\ConfirmAccountEmail;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class SendConfirmAccountEmail implements ShouldQueue
{
    public function handle(Registered $event)
    {
        Mail::send(
            new ConfirmAccountEmail($event->user)
        );
    }
}
