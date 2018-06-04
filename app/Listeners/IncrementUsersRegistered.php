<?php

namespace App\Listeners;

use App\Models\SiteStats;

class IncrementUsersRegistered
{
    public function handle()
    {
        SiteStats::incrementUsersRegistered();
    }
}
