<?php

namespace Madbox99\FilamentForms\Resources\FormResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Madbox99\FilamentForms\Resources\FormResource;

class EditForm extends EditRecord
{
    protected static string $resource = FormResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
