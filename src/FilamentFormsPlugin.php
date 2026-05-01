<?php

namespace Madbox99\FilamentForms;

use Filament\Contracts\Plugin;
use Filament\Panel;
use Madbox99\FilamentForms\Resources\FormResource;
use Madbox99\FilamentForms\Resources\FormSubmissionResource;

class FilamentFormsPlugin implements Plugin
{
    protected ?string $navigationGroup = 'Forms';

    protected ?int $navigationSort = 1;

    public function getId(): string
    {
        return 'filament-forms-plugin';
    }

    public static function make(): static
    {
        return app(static::class);
    }

    public function navigationGroup(?string $group): static
    {
        $this->navigationGroup = $group;

        return $this;
    }

    public function navigationSort(?int $sort): static
    {
        $this->navigationSort = $sort;

        return $this;
    }

    public function getNavigationGroup(): ?string
    {
        return $this->navigationGroup;
    }

    public function getNavigationSort(): ?int
    {
        return $this->navigationSort;
    }

    public function register(Panel $panel): void
    {
        $panel->resources([
            FormResource::class,
            FormSubmissionResource::class,
        ]);
    }

    public function boot(Panel $panel): void
    {
        //
    }
}
