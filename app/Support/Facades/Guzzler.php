<?php

namespace App\Support\Facades;

use App\Support\GuzzleWrapper;
use Illuminate\Support\Facades\Facade;

class Guzzler extends Facade
{
    protected static function getFacadeAccessor()
    {
        return GuzzleWrapper::class;
    }
}
