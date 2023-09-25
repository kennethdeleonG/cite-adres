<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use App\Filament\Widgets\StatsOverview;
use Filament\Pages\Dashboard as BasePage;

class Dashboard extends BasePage
{
    protected function getWidgets(): array
    {
        return [
            StatsOverview::class,
        ];
    }
}
