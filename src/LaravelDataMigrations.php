<?php

namespace CrixuAMG\LaravelDataMigrations;

use CrixuAMG\LaravelDataMigrations\DataMigrations\AbstractDataMigration;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RegexIterator;

class LaravelDataMigrations
{
    public static function migrate(Command $command)
    {
        $dataMigrations = self::getDataMigrations();

        foreach ($dataMigrations as $dataMigration) {
            /** @var AbstractDataMigration $dataMigrationInstance */
            $dataMigrationInstance = new $dataMigration($command);

            if ($dataMigrationInstance->shouldMigrate()) {
                $dataMigrationInstance->handle();
            }
        }
    }

    private static function getDataMigrations()
    {
        $path = base_path().'/app/DataMigrations';
        $dataMigrations = [];

        if (!File::exists($path)) {
            return [];
        }

        $allFiles = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path));
        $phpFiles = new RegexIterator($allFiles, '/\.php$/');
        foreach ($phpFiles as $phpFile) {
            $content = file_get_contents($phpFile->getRealPath());
            $tokens = token_get_all($content);
            $namespace = '';
            for ($index = 0; isset($tokens[$index]); $index++) {
                if (!isset($tokens[$index][0])) {
                    continue;
                }
                if (T_NAMESPACE === $tokens[$index][0]) {
                    $index += 2; // Skip namespace keyword and whitespace
                    while (isset($tokens[$index]) && is_array($tokens[$index])) {
                        $namespace .= $tokens[$index++][1];
                    }
                }
                if (T_CLASS === $tokens[$index][0] && T_WHITESPACE === $tokens[$index + 1][0] && T_STRING === $tokens[$index + 2][0]) {
                    $index += 2; // Skip class keyword and whitespace
                    $dataMigrations[] = $namespace.'\\'.$tokens[$index][1];

                    # break if you have one class per file (psr-4 compliant)
                    # otherwise you'll need to handle class constants (Foo::class)
                    break;
                }
            }
        }

        return $dataMigrations;
    }
}
