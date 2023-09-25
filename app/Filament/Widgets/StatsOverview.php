<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;

class StatsOverview extends BaseWidget
{
    protected function getCards(): array
    {
        return [
            Card::make('Users', '10'),
            // ->icon('heroicon-o-users'),
            Card::make('Documents', '57'),
            // ->icon('heroicon-o-document-duplicate'),
            Card::make('Tasks', '4'),
            // ->icon('heroicon-o-document'),
            Card::make('Recycle Bin', '14'),
            // ->icon('heroicon-o-trash'),
            Card::make('Storage', '25%'),
        ];
    }
}
