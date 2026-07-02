<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LinkResource\Pages;
use App\Filament\Resources\LinkResource\RelationManagers\ClicksRelationManager;
use App\Models\Link;
use App\Rules\HttpUrl;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class LinkResource extends Resource
{
    protected static ?string $model = Link::class;

    protected static ?string $navigationIcon = 'heroicon-o-link';

    protected static ?string $recordTitleAttribute = 'short_code';

    protected static ?string $navigationLabel = 'Ссылки';

    protected static ?string $modelLabel = 'ссылка';

    protected static ?string $pluralModelLabel = 'ссылки';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Новая ссылка')
                    ->description('Укажите оригинальный URL — короткий код будет создан автоматически.')
                    ->icon('heroicon-o-link')
                    ->schema([
                        Forms\Components\TextInput::make('original_url')
                            ->label('Оригинальный URL')
                            ->url()
                            ->rules([new HttpUrl])
                            ->required()
                            ->maxLength(2048)
                            ->placeholder('https://example.com/page')
                            ->prefixIcon('heroicon-o-globe-alt')
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('short_code')
                    ->label('Короткая ссылка')
                    ->icon('heroicon-m-link')
                    ->color('primary')
                    ->weight('medium')
                    ->url(fn (Link $record): string => $record->short_url)
                    ->openUrlInNewTab()
                    ->copyable()
                    ->copyableState(fn (Link $record): string => $record->short_url)
                    ->copyMessage('Ссылка скопирована')
                    ->searchable(),
                Tables\Columns\TextColumn::make('original_url')
                    ->label('Оригинальный URL')
                    ->limit(50)
                    ->color('gray')
                    ->url(fn (Link $record): string => $record->original_url)
                    ->openUrlInNewTab()
                    ->searchable(),
                Tables\Columns\TextColumn::make('clicks_count')
                    ->label('Переходы')
                    ->counts('clicks')
                    ->badge()
                    ->color('success')
                    ->icon('heroicon-m-cursor-arrow-rays')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Создана')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->emptyStateHeading('Пока нет ссылок')
            ->emptyStateDescription('Создайте первую короткую ссылку.')
            ->emptyStateIcon('heroicon-o-link')
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            ClicksRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLinks::route('/'),
            'create' => Pages\CreateLink::route('/create'),
            'view' => Pages\ViewLink::route('/{record}'),
        ];
    }

    /**
     * Restrict every query to the links owned by the current user.
     */
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('user_id', Auth::id());
    }
}
