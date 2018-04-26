<?php

namespace App\Support\Enums;

use LogicException;

class Months extends Enum
{
    const JANUARY = 'january';

    const FEBRUARY = 'february';

    const MARCH = 'march';

    const APRIL = 'april';

    const MAY = 'may';

    const JUNE = 'june';

    const JULY = 'july';

    const AUGUST = 'august';

    const SEPTEMBER = 'september';

    const OCTOBER = 'october';

    const NOVEMBER = 'november';

    const DECEMBER = 'december';

    public static function toInt($month)
    {
        switch ($month) {
            case 'january': return 1;
            case 'february': return 2;
            case 'march': return 3;
            case 'april': return 4;
            case 'may': return 5;
            case 'june': return 6;
            case 'july': return 7;
            case 'august': return 8;
            case 'september': return 9;
            case 'october': return 10;
            case 'november': return 11;
            case 'december': return 12;
        }

        throw new LogicException('Invalid month');
    }
}
