<?php

namespace JsonNaN\LaravelRiotLolApi\Commands;

use Illuminate\Console\Command;

class LaravelRiotLolApiCommand extends Command
{
    public $signature = 'laravel-riot-lol-api:test';

    public $description = 'Command for test the package was installed correctly.';

    public function handle(): int
    {
        $this->comment('All good!');

        return self::SUCCESS;
    }
}
