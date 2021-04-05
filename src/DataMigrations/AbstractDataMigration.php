<?php

namespace CrixuAMG\LaravelDataMigrations\DataMigrations;

use CrixuAMG\LaravelDataMigrations\Contracts\DataMigration;

abstract class AbstractDataMigration implements DataMigration
{
    abstract function migrate();

    function onFail(\Exception $exception)
    {
        // TODO: Implement onFail if required
    }

    function onSuccess()
    {
        // TODO: Implement onSuccess if required
    }

    public function handle()
    {
        try {
            $this->migrate();

            $this->onSuccess();

            $this->create([
                'name' => get_called_class(),
            ]);
        } catch (\Exception $exception) {
            $this->onFail($exception);
        }
    }
}
