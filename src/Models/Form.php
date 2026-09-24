<?php

namespace Madbox99\FilamentForms\Models;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * A táblanév `config()`-ból jön, amit a statikus elemzés nem tud feloldani:
 * a migrációból így egyetlen oszlopot sem ismer fel. Ezért sorolja fel őket
 * ez a blokk — enélkül a csomagot használó alkalmazásban minden mezőre
 * „undefined property" jön.
 *
 * @property int $id
 * @property string $title
 * @property string $slug
 * @property string|null $description
 * @property array<int, array<string, mixed>> $fields
 * @property string|null $success_message
 * @property string|null $notification_email
 * @property bool $is_active
 * @property CarbonInterface|null $created_at
 * @property CarbonInterface|null $updated_at
 */
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

    /** @return HasMany<FormSubmission, $this> */
    public function submissions(): HasMany
    {
        return $this->hasMany(FormSubmission::class);
    }

    public function unreadSubmissionsCount(): int
    {
        return $this->submissions()->where('is_read', false)->count();
    }
}
