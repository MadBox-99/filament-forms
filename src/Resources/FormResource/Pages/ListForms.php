<?php

namespace Madbox99\FilamentForms\Resources\FormResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Madbox99\FilamentForms\Resources\FormResource;

class ListForms extends ListRecords
{
    protected static string $resource = FormResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
