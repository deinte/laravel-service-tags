<?php

namespace Deinte\LaravelServiceTags\Service;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Cache;
use ReflectionClass;

class ClassmapGenerator
{
    public const CACHE_KEY = 'classmap';

    public function __invoke(): array
    {
        $classmap = config('app.instance_of.classmap');

        if (null === $classmap || 0 === count($classmap)) {
            $fileManager = app(Filesystem::class);

            $classmapFile = app()->basePath('vendor/composer/autoload_classmap.php');
            $values = $fileManager->getRequire($classmapFile);

            $classmap = array_map(
                $this->mapClassMap(...),
                $this->findApplicableClasses($values),
            );

            config()->set('app.instance_of.classmap', $classmap);
        }

        return $classmap;
    }

    private function mapClassMap(string $class): array
    {
        return array_merge(
            [$class], // In case the class itself is passed
            class_implements($class, false) ?: [], // For provided interface FQCN
            class_parents($class, false) ?: [] // For provided parent class FQCN
        );
    }

    private function findApplicableClasses(array $classmapValues): array
    {
        $applicableKeys = array_keys(
            array_filter(
                $classmapValues,
                $this->filter(...),
                ARRAY_FILTER_USE_KEY,
            )
        );

        return array_combine($applicableKeys, $applicableKeys);
    }

    private function filter(string $class): bool
    {
        if ($this->classIsExcluded($class)) {
            return false;
        }

        if (!$this->classIsIncluded($class)) {
            return false;
        }

        if (!class_exists($class)) {
            return false;
        }

        if ((new ReflectionClass($class))->isAbstract()) {
            return false;
        }

        return true;
    }

    private function classIsExcluded(string $class): bool
    {
        $excludeNamespaces = config('app.instance_of.namespaces.exclude', []);

        foreach ($excludeNamespaces as $namespace) {
            if (str_starts_with($class, $namespace)) {
                return true;
            }
        }

        return false;
    }

    private function classIsIncluded(string $class): bool
    {
        $includeNamespaces = config('app.instance_of.namespaces.include', ['App\\']);

        foreach ($includeNamespaces as $namespace) {
            if (str_starts_with($class, $namespace)) {
                return true;
            }
        }

        return false;
    }
}
