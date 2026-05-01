<?php

namespace Madbox99\FilamentForms\Resources\FormSubmissionResource\Pages;

use Filament\Resources\Pages\ViewRecord;
use Madbox99\FilamentForms\Resources\FormSubmissionResource;

class ViewFormSubmission extends ViewRecord
{
    protected static string $resource = FormSubmissionResource::class;

    #[\Override]
    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['is_read'] = true;
        $this->record->update(['is_read' => true]);

        return $data;
    }
}
