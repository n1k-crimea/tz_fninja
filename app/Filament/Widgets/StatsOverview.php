<?php

namespace App\Filament\Widgets;

use App\Models\Click;
use App\Models\Link;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $linksCount = Link::where('user_id', Auth::id())->count();

        $clicksCount = Click::whereHas('link', function ($query) {
            $query->where('user_id', Auth::id());
        })->count();

        return [
            Stat::make('Мои ссылки', $linksCount)
                ->description('Всего создано')
                ->descriptionIcon('heroicon-m-link')
                ->color('primary'),
            Stat::make('Всего переходов', $clicksCount)
                ->description('По всем ссылкам')
                ->descriptionIcon('heroicon-m-cursor-arrow-rays')
                ->color('success'),
        ];
    }
}
