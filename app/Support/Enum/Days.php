<?php

namespace App\Support\Enums;

use LogicException;

class Days extends Enum
{
    const MONDAY = 'monday';

    const TUESDAY = 'tuesday';

    const WEDNESDAY = 'wednesday';

    const THURSDAY = 'thursday';

    const FRIDAY = 'friday';

    const SATURDAY = 'saturday';

    const SUNDAY = 'sunday';

    public static function toInt($day)
    {
        // Matches Carbon constants
        switch (strtolower($day)) {
            case static::SUNDAY: return 0;
            case static::MONDAY: return 1;
            case static::TUESDAY: return 2;
            case static::WEDNESDAY: return 3;
            case static::THURSDAY: return 4;
            case static::FRIDAY: return 5;
            case static::SATURDAY: return 6;
        }

        throw new LogicException('Invalid day');
    }

    public static function regex()
    {
        return '(monday|tuesday|wednesday|thursday|friday|saturday|sunday)';
    }
}
