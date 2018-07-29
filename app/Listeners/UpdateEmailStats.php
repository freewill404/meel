<?php

namespace App\Listeners;

use App\Events\EmailNotSent;
use App\Events\EmailSent;
use App\Events\UserAlmostOutOfEmails;
use App\Events\UserOutOfEmails;

class UpdateEmailStats
{
    /**
     * @param $event EmailSent|EmailNotSent
     */
    public function handle($event)
    {
        $event instanceof EmailSent
            ? $this->sent($event)
            : $this->notSent($event);
    }

    protected function sent(EmailSent $event)
    {
        $user = $event->user;

        $user->decrement('emails_left');

        if ($user->emails_left === 9) {
            UserAlmostOutOfEmails::dispatch($user);
        } elseif ($user->emails_left === 0) {
            UserOutOfEmails::dispatch($user);
        }
    }

    protected function notSent(EmailNotSent $event)
    {
        //
    }
}
