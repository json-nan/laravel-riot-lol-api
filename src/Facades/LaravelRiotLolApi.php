<?php

namespace JsonNaN\LaravelRiotLolApi\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \JsonNaN\LaravelRiotLolApi\LaravelRiotLolApi
 */
class LaravelRiotLolApi extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \JsonNaN\LaravelRiotLolApi\LaravelRiotLolApi::class;
    }
}
