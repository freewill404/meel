<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ConfirmAccountEmail extends Mailable
{
    use Queueable, SerializesModels;

    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;

        $this->to($user->email)
            ->subject('Confirm your account | Meel.me');
    }

    public function build()
    {
        return $this->view('email.confirm-account', [
            'user' => $this->user,
        ]);
    }
}
