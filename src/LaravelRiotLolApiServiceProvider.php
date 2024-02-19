<?php

namespace JsonNaN\LaravelRiotLolApi;

use JsonNaN\LaravelRiotLolApi\Commands\LaravelRiotLolApiCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class LaravelRiotLolApiServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('laravel-riot-lol-api')
            ->hasConfigFile()
            ->hasCommand(LaravelRiotLolApiCommand::class);
    }
}
