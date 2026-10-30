<?php

declare(strict_types=1);

namespace Madbox99\FilamentForms\Resources;

use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Madbox99\FilamentForms\Models\Form as FormModel;
use Madbox99\FilamentForms\Resources\FormResource\Pages;
use UnitEnum;

class FormResource extends Resource
{
    protected static ?string $model = FormModel::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?int $navigationSort = 1;

    #[\Override]
    public static function getNavigationGroup(): string|UnitEnum|null
    {
        return static::$navigationGroup ?? __('filament-forms::forms.navigation_group');
    }

    #[\Override]
    public static function getNavigationLabel(): string
    {
        return static::$navigationLabel ?? __('filament-forms::forms.navigation_label');
    }

    #[\Override]
    public static function getModelLabel(): string
    {
        return static::$modelLabel ?? __('filament-forms::forms.model_label');
    }

    #[\Override]
    public static function getPluralModelLabel(): string
    {
        return static::$pluralModelLabel ?? __('filament-forms::forms.plural_model_label');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Schemas\Components\Section::make(__('filament-forms::forms.sections.details'))
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->label(__('filament-forms::forms.fields.title'))
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (Set $set, ?string $state): mixed => $set('slug', Str::slug($state ?? ''))),
                        Forms\Components\TextInput::make('slug')
                            ->label(__('filament-forms::forms.fields.slug'))
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),
                        Forms\Components\Textarea::make('description')
                            ->label(__('filament-forms::forms.fields.description'))
                            ->rows(2),
                        Forms\Components\Textarea::make('success_message')
                            ->label(__('filament-forms::forms.fields.success_message'))
                            ->rows(2),
                        Forms\Components\TextInput::make('notification_email')
                            ->label(__('filament-forms::forms.fields.notification_email'))
                            ->email()
                            ->helperText(__('filament-forms::forms.fields.notification_email_helper')),
                        Forms\Components\Toggle::make('is_active')
                            ->label(__('filament-forms::forms.fields.is_active'))
                            ->default(true),
                    ])->columns(2),
                Schemas\Components\Section::make(__('filament-forms::forms.sections.fields'))
                    ->schema([
                        Forms\Components\Repeater::make('fields')
                            ->label('')
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->label(__('filament-forms::forms.form_fields.name'))
                                    ->required()
                                    ->alphaDash(),
                                Forms\Components\TextInput::make('label')
                                    ->label(__('filament-forms::forms.form_fields.label'))
                                    ->required(),
                                Forms\Components\Select::make('type')
                                    ->label(__('filament-forms::forms.form_fields.type'))
                                    ->options([
                                        'text' => __('filament-forms::forms.field_types.text'),
                                        'email' => __('filament-forms::forms.field_types.email'),
                                        'phone' => __('filament-forms::forms.field_types.phone'),
                                        'textarea' => __('filament-forms::forms.field_types.textarea'),
                                        'select' => __('filament-forms::forms.field_types.select'),
                                        'checkbox' => __('filament-forms::forms.field_types.checkbox'),
                                        'radio' => __('filament-forms::forms.field_types.radio'),
                                        'file' => __('filament-forms::forms.field_types.file'),
                                        'date' => __('filament-forms::forms.field_types.date'),
                                        'number' => __('filament-forms::forms.field_types.number'),
                                    ])
                                    ->required()
                                    ->live(),
                                Forms\Components\Textarea::make('options')
                                    ->label(__('filament-forms::forms.form_fields.options'))
                                    ->rows(3)
                                    ->visible(fn (Get $get): bool => in_array($get('type'), ['select', 'radio'])),
                                Forms\Components\Toggle::make('required')
                                    ->label(__('filament-forms::forms.form_fields.required'))
                                    ->default(false),
                                Forms\Components\TextInput::make('placeholder')
                                    ->label(__('filament-forms::forms.form_fields.placeholder')),
                            ])
                            ->columns(3)
                            ->reorderable()
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string => $state['label'] ?? null)
                            ->defaultItems(1),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label(__('filament-forms::forms.table.title'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('slug')
                    ->label(__('filament-forms::forms.table.slug')),
                Tables\Columns\TextColumn::make('submissions_count')
                    ->label(__('filament-forms::forms.table.submissions'))
                    ->counts('submissions'),
                Tables\Columns\IconColumn::make('is_active')
                    ->label(__('filament-forms::forms.table.is_active'))
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('filament-forms::forms.table.created_at'))
                    ->dateTime('Y-m-d')
                    ->sortable(),
            ])
            ->actions([
                EditAction::make(),
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
            'index' => Pages\ListForms::route('/'),
            'create' => Pages\CreateForm::route('/create'),
            'edit' => Pages\EditForm::route('/{record}/edit'),
        ];
    }
}
