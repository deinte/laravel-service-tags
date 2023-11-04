<?php

namespace Deinte\LaravelServiceTags\Service;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Cache;
use ReflectionClass;
use Throwable;

class ServiceTagger
{
    public function __construct(
        private readonly ClassmapGenerator $classmapGenerator,
    ) {
    }

    function __invoke(array|string $instancesOfFQCN, array|string $tags): void
    {
        if (is_array($instancesOfFQCN)) {
            foreach ($instancesOfFQCN as $instance) {
                ($this)($instance, $tags);
            }

            return;
        }

        if (is_array($tags)) {
            foreach ($tags as $tag) {
                ($this)($instancesOfFQCN, $tag);
            }

            return;
        }

        // Determine if cached results should be used
        $useCachedServices = config('app.instance_of.cache', false);
        // Define the namespaces that should be checked for tagging
        $taggedServices = [];

        if ($useCachedServices && Cache::has(ClassmapGenerator::CACHE_KEY)) {
            $taggedServices = Cache::get(ClassmapGenerator::CACHE_KEY);
        }

        if (isset($taggedServices[$tags][$instancesOfFQCN])) {
            app()->tag($taggedServices[$tags][$instancesOfFQCN], $tags);

            return;
        }

        $classmap = ($this->classmapGenerator)();

        $taggedServices[$tags][$instancesOfFQCN] = [];

        foreach ($classmap as $class => $value) {
            if (!in_array($instancesOfFQCN, $value, true)) {
                continue;
            }

            $taggedServices[$tags][$instancesOfFQCN][] = $class;

            app()->tag($class, $tags);
        }

        if ($useCachedServices) {
            Cache::set(ClassmapGenerator::CACHE_KEY, $taggedServices);
        }
    }
}
