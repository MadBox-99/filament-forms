<?php

declare(strict_types=1);

namespace Madbox99\FilamentForms;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class FilamentFormsServiceProvider extends PackageServiceProvider
{
    public static string $name = 'filament-forms';

    public function configurePackage(Package $package): void
    {
        $package->name(static::$name)
            ->hasConfigFile()
            ->discoversMigrations()
            ->runsMigrations();
    }
}
