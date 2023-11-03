<?php

namespace Deinte\LaravelServiceTags;

use Deinte\LaravelServiceTags\Commands\LaravelServiceTagsCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class LaravelServiceTagsServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('laravel-service-tags')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_laravel-service-tags_table')
            ->hasCommand(LaravelServiceTagsCommand::class);
    }
}
