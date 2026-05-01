<?php

namespace Madbox99\FilamentForms\Resources;

use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms;
use Filament\Resources\Resource;
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

    protected static string|UnitEnum|null $navigationGroup = 'Forms';

    protected static ?string $navigationLabel = 'Forms';

    protected static ?string $modelLabel = 'Form';

    protected static ?string $pluralModelLabel = 'Forms';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Forms\Components\Section::make('Details')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->label('Title')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state ?? ''))),
                        Forms\Components\TextInput::make('slug')
                            ->label('URL Slug')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),
                        Forms\Components\Textarea::make('description')
                            ->label('Description')
                            ->rows(2),
                        Forms\Components\Textarea::make('success_message')
                            ->label('Success Message')
                            ->rows(2),
                        Forms\Components\TextInput::make('notification_email')
                            ->label('Notification Email')
                            ->email()
                            ->helperText('Receive notification emails for new submissions'),
                        Forms\Components\Toggle::make('is_active')
                            ->label('Active')
                            ->default(true),
                    ])->columns(2),
                Forms\Components\Section::make('Fields')
                    ->schema([
                        Forms\Components\Repeater::make('fields')
                            ->label('')
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->label('Field Name (technical)')
                                    ->required()
                                    ->alphaDash(),
                                Forms\Components\TextInput::make('label')
                                    ->label('Label')
                                    ->required(),
                                Forms\Components\Select::make('type')
                                    ->label('Type')
                                    ->options([
                                        'text' => 'Text',
                                        'email' => 'Email',
                                        'phone' => 'Phone',
                                        'textarea' => 'Textarea',
                                        'select' => 'Select',
                                        'checkbox' => 'Checkbox',
                                        'radio' => 'Radio',
                                        'file' => 'File Upload',
                                        'date' => 'Date',
                                        'number' => 'Number',
                                    ])
                                    ->required()
                                    ->live(),
                                Forms\Components\Textarea::make('options')
                                    ->label('Options (one per line)')
                                    ->rows(3)
                                    ->visible(fn (Get $get): bool => in_array($get('type'), ['select', 'radio'])),
                                Forms\Components\Toggle::make('required')
                                    ->label('Required')
                                    ->default(false),
                                Forms\Components\TextInput::make('placeholder')
                                    ->label('Placeholder'),
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
                    ->label('Title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('slug')
                    ->label('Slug'),
                Tables\Columns\TextColumn::make('submissions_count')
                    ->label('Submissions')
                    ->counts('submissions'),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime('Y-m-d')
                    ->sortable(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkRecordActions([
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
