<?php

namespace App\Meel;

use Illuminate\Support\Carbon;
use LogicException;

class AbsoluteTimeInterpretation
{
    protected $string;

    protected $time;

    public function __construct(string $string)
    {
        $this->string = $string;

        $this->time = $this->interpretAbsoluteTime($string);
    }

    public function isAbsoluteTime()
    {
        return ! $this->time;
    }

    public function getTime(): Carbon
    {
        if (! $this->isAbsoluteTime()) {
            throw new LogicException('The given input is not absolute');
        }

        return now();
    }

    protected function interpretAbsoluteTime($string)
    {


        return false;
    }
}
