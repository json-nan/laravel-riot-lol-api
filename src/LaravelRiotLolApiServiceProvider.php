<?php

namespace JsonNaN\LaravelRiotLolApi;

use Illuminate\Support\Facades\Http;
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

    public function boot()
    {
        parent::boot();

        Http::macro('riotLolApi', function () {
            return Http::withHeaders([
                'X-Riot-Token' => config('riot-lol-api.api_key'),
            ]);
        });
    }
}
