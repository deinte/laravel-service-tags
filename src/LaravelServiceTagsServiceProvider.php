<?php

namespace Deinte\LaravelServiceTags;

use Deinte\LaravelServiceTags\Commands\LaravelServiceTagsCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class LaravelServiceTagsServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package->name('laravel-service-tags');
    }
}
