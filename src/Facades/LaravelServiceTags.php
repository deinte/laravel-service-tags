<?php

namespace Deinte\LaravelServiceTags\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Deinte\LaravelServiceTags\LaravelServiceTags
 */
class LaravelServiceTags extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Deinte\LaravelServiceTags\LaravelServiceTags::class;
    }
}
