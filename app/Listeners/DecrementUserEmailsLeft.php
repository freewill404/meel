<?php

namespace App\Listeners;

use App\Events\EmailSent;
use App\Events\UserAlmostOutOfEmails;
use App\Events\UserOutOfEmails;

class DecrementUserEmailsLeft
{
    public function handle(EmailSent $event)
    {
        $user = $event->schedule->user;

        $user->decrement('emails_left');

        if ($user->emails_left === 9) {
            UserAlmostOutOfEmails::dispatch($user);
        } elseif ($user->emails_left === 0) {
            UserOutOfEmails::dispatch($user);
        }
    }
}
