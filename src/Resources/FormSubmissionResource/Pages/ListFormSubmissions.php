<?php

declare(strict_types=1);

namespace Madbox99\FilamentForms\Resources\FormSubmissionResource\Pages;

use Filament\Resources\Pages\ListRecords;
use Madbox99\FilamentForms\Resources\FormSubmissionResource;

class ListFormSubmissions extends ListRecords
{
    protected static string $resource = FormSubmissionResource::class;
}
