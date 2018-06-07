<?php

namespace App\Listeners;

use App\Events\EmailSent;

class DecrementUserEmailsLeft
{
    public function handle(EmailSent $event)
    {
        $user = $event->emailSchedule->user;

        $emailsLeftField = $user->free_emails_left
            ? 'free_emails_left'
            : 'paid_emails_left';

        $user->decrement($emailsLeftField);
    }
}
