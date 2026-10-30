<?php

namespace Madbox99\FilamentForms\Resources\FormSubmissionResource\Pages;

use Filament\Resources\Pages\ViewRecord;
use Madbox99\FilamentForms\Resources\FormSubmissionResource;

class ViewFormSubmission extends ViewRecord
{
    protected static string $resource = FormSubmissionResource::class;

    /**
     * Opening a submission marks it read. This runs in mount() because the
     * page shows an infolist, so the form (and its fill hooks) never loads.
     */
    #[\Override]
    public function mount(int|string $record): void
    {
        parent::mount($record);

        if (! $this->record->getAttribute('is_read')) {
            $this->record->update(['is_read' => true]);
        }
    }
}
