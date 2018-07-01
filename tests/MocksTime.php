<?php

namespace Tests;

use Carbon\Carbon;

trait MocksTime
{
    protected function progressTimeInMinutes($minutes = 1)
    {
        Carbon::setTestNow(
            now()->addMinutes($minutes)
        );

        return $this;
    }

    protected function progressTimeInHours($hours = 1)
    {
        Carbon::setTestNow(
            now()->addHours($hours)
        );

        return $this;
    }

    protected function rewindTimeInHours($hours = 1)
    {
        Carbon::setTestNow(
            now()->subHours($hours)
        );

        return $this;
    }

    protected function progressTimeInDays($days = 1)
    {
        Carbon::setTestNow(
            now()->addDays($days)
        );

        return $this;
    }
}
