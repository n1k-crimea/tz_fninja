<?php

namespace App\Filament\Resources\LinkResource\Pages;

use App\Filament\Resources\LinkResource;
use Filament\Actions;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;

class ViewLink extends ViewRecord
{
    protected static string $resource = LinkResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Статистика ссылки')
                    ->icon('heroicon-o-chart-bar')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('short_url')
                            ->label('Короткая ссылка')
                            ->icon('heroicon-m-link')
                            ->color('primary')
                            ->url(fn (): string => $this->record->short_url)
                            ->openUrlInNewTab()
                            ->copyable(),
                        TextEntry::make('clicks_count')
                            ->label('Всего переходов')
                            ->state(fn (): int => $this->record->clicks()->count())
                            ->badge()
                            ->color('success'),
                        TextEntry::make('original_url')
                            ->label('Оригинальный URL')
                            ->columnSpanFull()
                            ->url(fn (): string => $this->record->original_url)
                            ->openUrlInNewTab(),
                        TextEntry::make('created_at')
                            ->label('Создана')
                            ->dateTime('d.m.Y H:i'),
                    ]),
            ]);
    }
}
