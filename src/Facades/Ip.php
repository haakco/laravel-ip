<?php

declare(strict_types=1);

namespace HaakCo\Ip\Facades;

use Illuminate\Support\Facades\Facade;

class Ip extends Facade
{
    /**
     * Get the registered name of the component.
     */
    protected static function getFacadeAccessor(): string
    {
        return 'ip';
    }
}
