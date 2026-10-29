<?php

declare(strict_types=1);

namespace Madbox99\FilamentForms\Resources;

use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Madbox99\FilamentForms\Models\FormSubmission;
use Madbox99\FilamentForms\Resources\FormSubmissionResource\Pages;
use UnitEnum;

class FormSubmissionResource extends Resource
{
    protected static ?string $model = FormSubmission::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-inbox';

    protected static ?int $navigationSort = 2;

    #[\Override]
    public static function getNavigationGroup(): string|UnitEnum|null
    {
        return static::$navigationGroup ?? __('filament-forms::forms.navigation_group');
    }

    #[\Override]
    public static function getNavigationLabel(): string
    {
        return static::$navigationLabel ?? __('filament-forms::submissions.navigation_label');
    }

    #[\Override]
    public static function getModelLabel(): string
    {
        return static::$modelLabel ?? __('filament-forms::submissions.model_label');
    }

    #[\Override]
    public static function getPluralModelLabel(): string
    {
        return static::$pluralModelLabel ?? __('filament-forms::submissions.plural_model_label');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Schemas\Components\Section::make()
                    ->schema([
                        Forms\Components\Select::make('form_id')
                            ->label(__('filament-forms::submissions.fields.form'))
                            ->relationship('form', 'title')
                            ->disabled(),
                        Forms\Components\Toggle::make('is_read')
                            ->label(__('filament-forms::submissions.fields.is_read')),
                        Forms\Components\KeyValue::make('data')
                            ->label(__('filament-forms::submissions.fields.data'))
                            ->disabled()
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('form.title')
                    ->label(__('filament-forms::submissions.fields.form'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_read')
                    ->label(__('filament-forms::submissions.fields.is_read'))
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('filament-forms::submissions.fields.created_at'))
                    ->dateTime('Y-m-d H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('form')
                    ->relationship('form', 'title')
                    ->label(__('filament-forms::submissions.fields.form')),
                Tables\Filters\TernaryFilter::make('is_read')
                    ->label(__('filament-forms::submissions.fields.is_read')),
            ])
            ->actions([
                ViewAction::make(),
                Action::make('markAsRead')
                    ->label(__('filament-forms::submissions.actions.mark_as_read'))
                    ->icon('heroicon-o-check')
                    ->action(fn (FormSubmission $record) => $record->update(['is_read' => true]))
                    ->visible(fn (FormSubmission $record): bool => ! $record->is_read),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFormSubmissions::route('/'),
            'view' => Pages\ViewFormSubmission::route('/{record}'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }
}
