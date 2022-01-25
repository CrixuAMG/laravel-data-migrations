<?php

namespace CrixuAMG\LaravelDataMigrations\DataMigrations;

use CrixuAMG\LaravelDataMigrations\Contracts\DataMigration as DataMigrationContract;
use CrixuAMG\LaravelDataMigrations\Models\DataMigration as DataMigrationModel;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

abstract class AbstractDataMigration implements DataMigrationContract
{
    protected $command;

    public function __construct(Command $command)
    {
        $this->command = $command;
    }

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
            $this->command->info('Migrating '.$this->getDataMigrationName().'...');

            $this->migrate();

            $this->onSuccess();

            DataMigrationModel::firstOrCreate([
                'name'  => $this->getDataMigrationName(),
                'batch' => $this->getBatchCode(),
            ]);

            $this->command->info('Finished migrating '.$this->getDataMigrationName());
        } catch (\Exception $exception) {
            $this->command->error('An error occurred while migrating '.$this->getDataMigrationName().'');

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
