<?php

namespace Madbox99\FilamentForms\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Form extends Model
{
    protected $guarded = [];

    #[\Override]
    public function getTable(): string
    {
        return config('filament-forms.table_names.forms', 'forms');
    }

    #[\Override]
    protected function casts(): array
    {
        return [
            'fields' => 'array',
            'is_active' => 'boolean',
        ];
    }

    public function submissions(): HasMany
    {
        return $this->hasMany(FormSubmission::class);
    }

    public function unreadSubmissionsCount(): int
    {
        return $this->submissions()->where('is_read', false)->count();
    }
}
