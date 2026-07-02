<?php

namespace App\Filament\Resources\LinkResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class ClicksRelationManager extends RelationManager
{
    protected static string $relationship = 'clicks';

    protected static ?string $title = 'Переходы';

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('ip_address')
            ->columns([
                Tables\Columns\TextColumn::make('ip_address')
                    ->label('IP-адрес')
                    ->icon('heroicon-m-globe-alt')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Дата и время')
                    ->dateTime('d.m.Y H:i:s')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->emptyStateHeading('Переходов пока нет')
            ->emptyStateIcon('heroicon-o-cursor-arrow-rays')
            ->paginated([10, 25, 50]);
    }
}
