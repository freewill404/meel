<?php

namespace App\Meel;

use Carbon\Carbon;
use RuntimeException;

class DateTimeDiff
{
    protected $value;

    protected $now;

    protected $nextOccurrence;

    public function __construct($now, $nextOccurrence)
    {
        $this->now = Carbon::parse((string) $now)->second(0);

        $this->nextOccurrence = Carbon::parse((string) $nextOccurrence);

        if ($this->nextOccurrence->lessThanOrEqualTo($this->now)) {
            throw new RuntimeException('Next occurrence has to be in the future');
        }

        $this->value = $this->parse();
    }

    public function diff()
    {
        return $this->value;
    }

    private function parse()
    {
        $minutesDiff = $this->now->diffInMinutes($this->nextOccurrence) % 60;

        $hoursDiff = $this->now->diffInHours($this->nextOccurrence);

        if ($hoursDiff === 0) {
            return $this->onlyMinutes($minutesDiff);
        }

        if ($hoursDiff < 24) {
            return $this->onlyMinutesAndHours($minutesDiff, $hoursDiff);
        }

        return $this->at();
    }

    private function at()
    {
        $dayDiff = $this->now->copy()->setTimeFromTimeString('00:00:00')->diffInDays(
            $this->nextOccurrence->copy()->setTimeFromTimeString('23:59:59')
        );

        if ($dayDiff === 0) {
            return 'today at '.$this->nextOccurrence->format('H:i');
        }

        if ($dayDiff === 1) {
            return 'tomorrow at '.$this->nextOccurrence->format('H:i');
        }

        if ($dayDiff <= 7) {
            $thisOrNext = $dayDiff === 7 || $this->nextOccurrence->dayOfWeekIso < $this->now->dayOfWeekIso ? 'next ' : 'this ';

            return $thisOrNext.$this->nextOccurrence->format('l').' at '.$this->nextOccurrence->format('H:i');
        }

        return $this->now->year === $this->nextOccurrence->year
            ? 'on '.$this->nextOccurrence->format('l \t\h\e jS \o\f F, \a\t H:i')
            : 'on '.$this->nextOccurrence->format('l \t\h\e jS \o\f F, Y, \a\t H:i');
    }

    private function onlyMinutes(int $minutes)
    {
        if ($minutes === 1) {
            return 'in 1 minute';
        }

        if ($minutes < 5) {
            return 'in a few minutes';
        }

        if (in_array($minutes, [5, 10, 15, 20, 30, 45])) {
            return "in $minutes minutes";
        }

        return $this->at();
    }

    private function onlyMinutesAndHours(int $minutes, int $hours)
    {
        if ($hours > 3) {
            return $this->at();
        }

        if ($minutes === 0) {
            return "in $hours hour".($hours === 1 ? '' : 's');
        }

        if (in_array($minutes, [5, 10, 15, 20, 30, 45])) {
            return "in $hours hour".($hours === 1 ? '' : 's')." and $minutes minutes";
        }

        return $this->at();
    }
}
