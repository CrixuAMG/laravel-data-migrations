<?php

namespace CrixuAMG\LaravelDataMigrations\Console\Commands;

use CrixuAMG\LaravelDataMigrations\LaravelDataMigrations;
use Illuminate\Console\Command;

class RunDataMigration extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'data-migration:migrate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate all data migrations';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        LaravelDataMigrations::migrate(
            // Send the current instance to the migrator, so it can output information to the console
            $this
        );

        return 0;
    }
}
