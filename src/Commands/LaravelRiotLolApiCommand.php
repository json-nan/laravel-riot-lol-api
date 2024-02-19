<?php

namespace JsonNaN\LaravelRiotLolApi\Commands;

use Illuminate\Console\Command;

class LaravelRiotLolApiCommand extends Command
{
    public $signature = 'laravel-riot-lol-api';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
