<?php

declare(strict_types=1);

use Madbox99\FilamentForms\Models\Form;
use Madbox99\FilamentForms\Models\FormSubmission;
use Madbox99\FilamentForms\Resources\FormSubmissionResource\Pages\ViewFormSubmission;

use function Pest\Livewire\livewire;

function submission(array $data): FormSubmission
{
    $form = Form::query()->create([
        'title' => 'Contact',
        'slug' => 'contact',
        'fields' => [
            ['name' => 'name', 'label' => 'Your name', 'type' => 'text'],
            ['name' => 'email', 'label' => 'Your e-mail', 'type' => 'email'],
            ['name' => 'phone', 'label' => 'Your phone', 'type' => 'phone'],
            ['name' => 'message', 'label' => 'Your message', 'type' => 'textarea'],
        ],
    ]);

    return $form->submissions()->create(['data' => $data]);
}

it('shows every value under its field label, in full', function (): void {
    $message = str_repeat('A long line of the message. ', 20)."\nSecond <b>line</b>";

    $submission = submission([
        'name' => 'Anna',
        'email' => 'anna@example.com',
        'message' => $message,
        'legacy' => 'kept',
    ]);

    livewire(ViewFormSubmission::class, ['record' => $submission->getKey()])
        ->assertSeeInOrder(['Your name', 'Anna', 'Your e-mail', 'anna@example.com', 'Your phone', '—', 'Your message'])
        ->assertSeeHtml('href="mailto:anna@example.com"')
        ->assertSeeHtml(e(str_repeat('A long line of the message. ', 20)).'<br />')
        ->assertSeeHtml('Second &lt;b&gt;line&lt;/b&gt;')
        ->assertSee('legacy')
        ->assertSee('kept');
});

it('marks the submission read when it is opened', function (): void {
    $submission = submission(['name' => 'Anna']);

    livewire(ViewFormSubmission::class, ['record' => $submission->getKey()]);

    expect($submission->refresh()->is_read)->toBeTrue();
});

it('shows the submission in hungarian', function (): void {
    app()->setLocale('hu');

    $submission = submission(['name' => 'Anna']);

    livewire(ViewFormSubmission::class, ['record' => $submission->getKey()])
        ->assertSee('Beküldött adatok')
        ->assertSee('Beküldve')
        ->assertSee('Olvasott');
});
