<?php

namespace Madbox99\FilamentForms\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FormSubmission extends Model
{
    protected $guarded = [];

    #[\Override]
    public function getTable(): string
    {
        return config('filament-forms.table_names.form_submissions', 'form_submissions');
    }

    #[\Override]
    protected function casts(): array
    {
        return [
            'data' => 'array',
            'is_read' => 'boolean',
        ];
    }

    public function form(): BelongsTo
    {
        return $this->belongsTo(Form::class);
    }
}
