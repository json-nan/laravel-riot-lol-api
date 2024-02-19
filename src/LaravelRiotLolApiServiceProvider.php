<?php

namespace JsonNaN\LaravelRiotLolApi;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use JsonNaN\LaravelRiotLolApi\Commands\LaravelRiotLolApiCommand;

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
