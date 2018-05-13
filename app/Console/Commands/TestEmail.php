<?php

namespace App\Console\Commands;

use App\Mail\ConfirmAccountEmail;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class TestEmail extends Command
{
    protected $signature = 'meel:test-email';

    protected $description = 'Send a test email';

    public function handle()
    {
        Mail::send(
            new ConfirmAccountEmail(User::findOrFail(1))
        );
    }
}
