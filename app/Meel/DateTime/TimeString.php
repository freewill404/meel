<?php

namespace App\Meel\DateTime;

use Carbon\Carbon;
use RuntimeException;

class TimeString
{
    protected $hours;

    protected $minutes;

    protected $seconds;

    public function __construct($string)
    {
        if ($string instanceof Carbon) {
            $string = $string->format('H:i:s');
        }

        if (! preg_match('/^\d\d?:\d\d?:\d\d?$/', $string)) {
            throw new RuntimeException('Invalid TimeString input: '.$string);
        }

        [$hours, $minutes, $seconds] = explode(':', $string);

        if ($hours < 0 || $hours > 23 || $minutes < 0 || $minutes > 59 || $seconds < 0 || $seconds > 59) {
            throw new RuntimeException('Invalid TimeString input: '.$string);
        }

        $this->hours = (int) $hours;

        $this->minutes = (int) $minutes;

        $this->seconds = (int) $seconds;
    }

    public function earlierThan($timeString): bool
    {
        return $this->difference($timeString) < 0;
    }

    public function earlierThanNow($timezone): bool
    {
        return $this->earlierThan(
            now($timezone)
        );
    }

    public function laterThan($timeString): bool
    {
        return $this->difference($timeString) > 0;
    }

    public function sameAs($timeString): bool
    {
        return $this->difference($timeString) === 0;
    }

    protected function difference($timeString)
    {
        $timeString = $timeString instanceof TimeString
            ? $timeString
            : new TimeString($timeString);

        return $this->toSeconds() - $timeString->toSeconds();
    }

    public function toSeconds(): int
    {
        return ($this->hours * 60 * 60) + ($this->minutes * 60) + $this->seconds;
    }

    public function toString()
    {
        return $this->__toString();
    }

    public function __toString()
    {
        return implode(':', [
            str_pad($this->hours,   2, '0', STR_PAD_LEFT),
            str_pad($this->minutes, 2, '0', STR_PAD_LEFT),
            str_pad($this->seconds, 2, '0', STR_PAD_LEFT),
        ]);
    }
}
