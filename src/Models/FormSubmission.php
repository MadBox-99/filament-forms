<?php

namespace Madbox99\FilamentForms\Models;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Lásd a {@see Form} megjegyzését: a táblanév `config()`-ból jön, ezért kell
 * felsorolni az oszlopokat.
 *
 * @property int $id
 * @property int $form_id
 * @property array<string, mixed> $data
 * @property bool $is_read
 * @property CarbonInterface|null $created_at
 * @property CarbonInterface|null $updated_at
 */
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

    /** @return BelongsTo<Form, $this> */
    public function form(): BelongsTo
    {
        return $this->belongsTo(Form::class);
    }
}
