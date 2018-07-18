<?php

namespace App\Meel\Schedules\WhenFormats;

use App\Meel\Schedules\WhenFormats\Relative\RelativeDays;
use App\Meel\Schedules\WhenFormats\Relative\RelativeHours;
use App\Meel\Schedules\WhenFormats\Relative\RelativeMinutes;
use App\Meel\Schedules\WhenFormats\Relative\RelativeMonths;
use App\Meel\Schedules\WhenFormats\Relative\RelativeNow;
use App\Meel\Schedules\WhenFormats\Relative\RelativeWeeks;
use App\Meel\Schedules\WhenFormats\Relative\RelativeWhenFormat;
use App\Meel\Schedules\WhenFormats\Relative\RelativeYears;
use App\Meel\Schedules\WhenString;
use App\Support\DateTime\DateString;
use App\Support\DateTime\SecondlessDateTimeString;
use App\Support\DateTime\SecondlessTimeString;
use LogicException;

class RelativeToNowInterpretation
{
    protected $formats = [
        RelativeNow::class,
        RelativeMinutes::class,
        RelativeHours::class,
        RelativeDays::class,
        RelativeWeeks::class,
        RelativeMonths::class,
        RelativeYears::class,
    ];

    protected $timezone;

    public function __construct(string $string, $timezone = null)
    {
        $this->timezone = $timezone;

        $preparedString = WhenString::prepare($string);

        $this->formats = collect($this->formats)
            ->map(function ($format) use ($preparedString, $timezone) {
                return new $format($preparedString, $timezone);
            });
    }

    public function isRelativeToNow(): bool
    {
        return $this->formats->contains(function (RelativeWhenFormat $format) {
            return $format->isUsableMatch();
        });
    }

    public function hasSpecifiedTime(): bool
    {
        return $this->formats->contains(function (RelativeWhenFormat $format) {
            return $format->isUsableMatch() && $format->specifiesTime();
        });
    }

    public function getDateString(): DateString
    {
        return $this->getDateTime()->getDateString();
    }

    public function getTimeString(): SecondlessTimeString
    {
        return $this->getDateTime()->getTimeString();
    }

    public function getDateTime(): SecondlessDateTimeString
    {
        if (! $this->isRelativeToNow()) {
            throw new LogicException('The given input is not relative to now');
        }

        $relativeNow = now($this->timezone);

        $this->formats->each(function (RelativeWhenFormat $format) use ($relativeNow) {
            $format->transformNow($relativeNow);
        });

        if (! $this->hasSpecifiedTime()) {
            $relativeNow->setTimeFromTimeString('08:00:00');
        }

        return SecondlessDateTimeString::fromCarbon($relativeNow);
    }
}
