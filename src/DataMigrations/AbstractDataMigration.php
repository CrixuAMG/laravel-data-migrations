<?php

namespace CrixuAMG\LaravelDataMigrations\DataMigrations;

use CrixuAMG\LaravelDataMigrations\Contracts\DataMigration as DataMigrationContract;
use CrixuAMG\LaravelDataMigrations\Models\DataMigration as DataMigrationModel;
use Illuminate\Support\Str;

abstract class AbstractDataMigration implements DataMigrationContract
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

            DataMigrationModel::firstOrCreate([
                'name'  => $this->getDataMigrationName(),
                'batch' => $this->getBatchCode(),
            ]);
        } catch (\Exception $exception) {
            $this->onFail($exception);

            throw $exception;
        }
    }

    private function getDataMigrationName()
    {
        $nameParts = explode("\\", get_called_class());

        $name = last($nameParts);
        $snakedName = Str::snake($name);

        return $snakedName;
    }

    private function getBatchCode()
    {
        /**
         * Get the last data migration, if it exists, return it's batch plus 1
         * Else, return 1
         */
        return optional(
            DataMigrationModel::orderByDesc('batch')->select('batch')->first()
        )->batch + 1;
    }

    public function shouldMigrate()
    {
        return !DataMigrationModel::whereName($this->getDataMigrationName())->exists();
    }
}
