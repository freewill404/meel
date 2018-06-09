<?php

namespace App\Events;

use App\Models\User;

class UserOutOfEmails extends BaseEvent
{
    public $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }
}
