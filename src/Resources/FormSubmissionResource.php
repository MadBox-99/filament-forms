<?php

namespace Madbox99\FilamentForms\Resources;

use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction;
use Filament\Forms;
use Filament\Resources\Resource;
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

    protected static string|UnitEnum|null $navigationGroup = 'Forms';

    protected static ?string $navigationLabel = 'Submissions';

    protected static ?string $modelLabel = 'Submission';

    protected static ?string $pluralModelLabel = 'Submissions';

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\Select::make('form_id')
                            ->label('Form')
                            ->relationship('form', 'title')
                            ->disabled(),
                        Forms\Components\Toggle::make('is_read')
                            ->label('Read'),
                        Forms\Components\KeyValue::make('data')
                            ->label('Submitted Data')
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
                    ->label('Form')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_read')
                    ->label('Read')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Submitted')
                    ->dateTime('Y-m-d H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('form')
                    ->relationship('form', 'title')
                    ->label('Form'),
                Tables\Filters\TernaryFilter::make('is_read')
                    ->label('Read'),
            ])
            ->recordActions([
                ViewAction::make(),
                Action::make('markAsRead')
                    ->label('Mark as Read')
                    ->icon('heroicon-o-check')
                    ->action(fn (FormSubmission $record) => $record->update(['is_read' => true]))
                    ->visible(fn (FormSubmission $record): bool => ! $record->is_read),
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
            'index' => Pages\ListFormSubmissions::route('/'),
            'view' => Pages\ViewFormSubmission::route('/{record}'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }
}
