<?php

use App\Support\Enums\Days;

/**
 * @param $path
 * @param null|string $disk
 *
 * @return string Absolute path to a file from the storage facade
 */
function storage_disk_file_path($path, $disk = null)
{
    $disk = $disk ?: env('FILESYSTEM_DRIVER');

    $storagePath = \Storage::disk($disk)->getDriver()->getAdapter()->getPathPrefix();

    return str_finish($storagePath, '/').ltrim($path, '/');
}

function interval(int $interval, $closure)
{
    $interval = ($interval === 0) ? 1 : $interval;

    static $calls = [];

    $caller = sha1(debug_backtrace()[0]['file'].'|'.debug_backtrace()[0]['line']);

    $callCount = $calls[$caller] ?? 1;

    if ($callCount % $interval === 0) {
        $closure();
    }

    $calls[$caller] = $callCount + 1;
}

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

function days_until_next(string $day, $timezone = null): int
{
    $dayInt = Days::toInt($day);

    $carbon = now($timezone);

    for ($i = 1; $i <= 7; $i++) {
        $carbon->addDays(1);

        if ($carbon->dayOfWeek === $dayInt) {
            return $i;
        }
    }

    throw new LogicException();
}
