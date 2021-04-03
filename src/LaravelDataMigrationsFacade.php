<?php

namespace CrixuAMG\LaravelDataMigrations;

use Illuminate\Support\Facades\Facade;

/**
 * @see \CrixuAMG\LaravelDataMigrations\Skeleton\SkeletonClass
 */
class LaravelDataMigrationsFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'laravel-data-migrations';
    }
}
