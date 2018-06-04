<?php

namespace App\Support\Enums;

use Illuminate\Support\Collection;

abstract class EnumArray extends Enum
{
    public static function all(): Collection
    {
        return collect(static::VALUES);
    }
}
