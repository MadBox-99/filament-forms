<?php

declare(strict_types=1);

use Illuminate\Support\Arr;
use Madbox99\FilamentForms\Models\Form;
use Madbox99\FilamentForms\Resources\FormResource;
use Madbox99\FilamentForms\Resources\FormResource\Pages\CreateForm;
use Madbox99\FilamentForms\Resources\FormResource\Pages\ListForms;
use Madbox99\FilamentForms\Resources\FormSubmissionResource;
use Madbox99\FilamentForms\Resources\FormSubmissionResource\Pages\ListFormSubmissions;

use function Pest\Livewire\livewire;

it('labels its resources in english by default', function (): void {
    expect(FormResource::getNavigationGroup())->toBe('Forms')
        ->and(FormResource::getNavigationLabel())->toBe('Forms')
        ->and(FormSubmissionResource::getNavigationLabel())->toBe('Submissions')
        ->and(FormSubmissionResource::getModelLabel())->toBe('Submission');

    livewire(CreateForm::class)
        ->assertSee('Details')
        ->assertSee('Notification Email')
        ->assertSee('Receive notification emails for new submissions');
});

it('translates the form editor into hungarian', function (): void {
    app()->setLocale('hu');

    expect(FormResource::getNavigationGroup())->toBe('Űrlapok')
        ->and(FormResource::getNavigationLabel())->toBe('Űrlapok')
        ->and(FormResource::getModelLabel())->toBe('Űrlap')
        ->and(FormResource::getPluralModelLabel())->toBe('Űrlapok');

    livewire(CreateForm::class)
        ->assertSee('Adatok')
        ->assertSee('Mezők')
        ->assertSee('URL-azonosító')
        ->assertSee('Értesítési e-mail')
        ->assertSee('Minden új beküldésről ide megy levél.')
        ->assertSee('Mezőnév (technikai)')
        ->assertDontSee('Field Name (technical)');
});

it('translates the lists into hungarian', function (): void {
    app()->setLocale('hu');

    Form::query()->create(['title' => 'Kapcsolat', 'slug' => 'kapcsolat', 'fields' => []]);

    livewire(ListForms::class)
        ->assertSee('Beküldések')
        ->assertSee('Létrehozva');

    expect(FormSubmissionResource::getNavigationLabel())->toBe('Beérkezett üzenetek')
        ->and(FormSubmissionResource::getModelLabel())->toBe('Beérkezett üzenet');

    livewire(ListFormSubmissions::class)
        ->assertSee('Beküldve')
        ->assertSee('Olvasott');
});

it('keeps labels that a subclass sets', function (): void {
    app()->setLocale('hu');

    $resource = new class extends FormSubmissionResource
    {
        protected static ?string $navigationLabel = 'Üzenetek';

        protected static ?string $modelLabel = 'Üzenet';
    };

    expect($resource::getNavigationLabel())->toBe('Üzenetek')
        ->and($resource::getModelLabel())->toBe('Üzenet')
        ->and($resource::getPluralModelLabel())->toBe('Beérkezett üzenetek');
});

it('has the same keys in every package language file', function (string $file): void {
    $flatten = fn (array $lines): array => array_keys(Arr::dot($lines));

    expect($flatten(require __DIR__."/../../resources/lang/hu/{$file}.php"))
        ->toBe($flatten(require __DIR__."/../../resources/lang/en/{$file}.php"));
})->with(['forms', 'submissions']);
