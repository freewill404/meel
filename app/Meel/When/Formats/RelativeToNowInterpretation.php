<?php

namespace App\Meel\When\Formats;

use App\Meel\When\Formats\Relative\RelativeDays;
use App\Meel\When\Formats\Relative\RelativeHours;
use App\Meel\When\Formats\Relative\RelativeMinutes;
use App\Meel\When\Formats\Relative\RelativeMonths;
use App\Meel\When\Formats\Relative\RelativeNow;
use App\Meel\When\Formats\Relative\RelativeWeeks;
use App\Meel\When\Formats\Relative\RelativeWhenFormat;
use App\Meel\When\Formats\Relative\RelativeYears;
use App\Meel\When\WhenString;
use App\Support\DateTime\DateString;
use App\Support\DateTime\SecondlessDateTimeString;
use App\Support\DateTime\SecondlessTimeString;
use Carbon\Carbon;
use RuntimeException;

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

    protected $now;

    public function __construct($now, string $writtenInput)
    {
        $this->now = $now instanceof Carbon
            ? $now->copy()
            : Carbon::parse($now);

        $preparedString = (new WhenString)->prepare($writtenInput);

        $this->formats = collect($this->formats)->map(function ($format) use ($preparedString) {
            return new $format($this->now->copy(), $preparedString);
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
            throw new RuntimeException('The given input is not relative to now');
        }

        $relativeNow = $this->now->copy();

        $this->formats->each(function (RelativeWhenFormat $format) use ($relativeNow) {
            $format->transformNow($relativeNow);
        });

        if (! $this->hasSpecifiedTime()) {
            $relativeNow->setTimeFromTimeString('08:00:00');
        }

        return SecondlessDateTimeString::fromCarbon($relativeNow);
    }
}
