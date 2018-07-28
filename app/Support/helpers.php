<?php

use App\Support\DateTime\SecondlessDateTimeString;

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

function secondless_now($timezone = null): SecondlessDateTimeString
{
    return SecondlessDateTimeString::now($timezone);
}
