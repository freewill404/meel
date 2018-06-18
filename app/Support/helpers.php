<?php

use App\Support\DateTime\SecondlessDateTimeString;
use App\Meel\EmailScheduleFormat;
use App\Models\EmailSchedule;

/**
 * Trigger a "dd()" after it has been called "timesCalled" times.
 *
 * @param $var
 *
 * @param int $timesCalled
 */
function dd_delay($var, int $timesCalled = 2)
{
    static $calls = [];

    $caller = sha1(debug_backtrace()[0]['file'].'|'.debug_backtrace()[0]['line']);

    $callCount = $calls[$caller] ?? 1;

    if ($callCount === $timesCalled) {
        dd($var);
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
