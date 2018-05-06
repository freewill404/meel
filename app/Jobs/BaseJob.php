<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Foundation\Bus\Dispatchable;

abstract class BaseJob
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    abstract public function handle();
}
