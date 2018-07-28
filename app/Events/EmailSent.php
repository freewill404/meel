<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Mail\Mailable;

class EmailSent extends BaseEvent
{
    public $user;

    public $email;

    public function __construct(User $user, Mailable $email)
    {
        $this->user = $user;

        $this->email = $email;
    }
}
