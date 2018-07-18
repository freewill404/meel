<?php

use App\Support\DateTime\SecondlessDateTimeString;
use App\Meel\Schedules\EmailScheduleFormat;
use App\Models\EmailSchedule;

/**
 * Trigger a "dd()" after it has been called "timesCalled" times.
 *
 * @param $vars
 *
 * @param int $timesCalled
 */
function dd_delay(int $timesCalled, ...$vars)
{
    static $calls = [];

    $caller = sha1(debug_backtrace()[0]['file'].'|'.debug_backtrace()[0]['line']);

    $callCount = $calls[$caller] ?? 1;

    if ($callCount === $timesCalled) {
        dd($vars);
    }

    $calls[$caller] = $callCount + 1;
}

function next_occurrence($when, $timezone = null): SecondlessDateTimeString
{
    if ($when instanceof EmailSchedule) {
        $timezone = $when->user->timezone;

        $when = $when->when;
    }

    $schedule = new EmailScheduleFormat($when, $timezone);

    $nextOccurrence = $schedule->nextOccurrence();

    if ($nextOccurrence === null) {
        throw new RuntimeException('Invalid "when": '.$when);
    }

    return $nextOccurrence;
}

function secondless_now($timezone = null): SecondlessDateTimeString
{
    return SecondlessDateTimeString::now($timezone);
}
