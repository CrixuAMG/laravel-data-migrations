<?php

namespace CrixuAMG\LaravelDataMigrations\Tests;

use Orchestra\Testbench\TestCase;
use CrixuAMG\LaravelDataMigrations\LaravelDataMigrationsServiceProvider;

class ExampleTest extends TestCase
{

    protected function getPackageProviders($app)
    {
        return [LaravelDataMigrationsServiceProvider::class];
    }

    /** @test */
    public function true_is_true()
    {
        $this->assertTrue(true);
    }
}
