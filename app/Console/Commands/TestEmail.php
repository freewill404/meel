<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class TestEmail extends Command
{
    protected $signature = 'meel:test-email';

    protected $description = 'Send a test email';

    public function handle()
    {
        Mail::raw('Sending emails with Mailgun and Laravel is easy!', function($message)
        {
            $message->subject('Mailgun and Laravel are awesome!');
            $message->from('no-reply@meel.me', 'Meel.me');
            $message->to('sjorsottjes@gmail.com');
        });
    }
}
