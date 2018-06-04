<?php

namespace App\Listeners;

use App\Models\SiteStats;

class IncrementEmailsSent
{
    public function handle()
    {
        SiteStats::incrementEmailsSent();
    }
}
