<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use App\Filament\Widgets\StatsOverview;
use App\Support\Concerns\FolderTrait;
use Filament\Pages\Dashboard as BasePage;

class Dashboard extends BasePage
{
    use FolderTrait;

    protected ?string $heading = '';

    protected static string $view = 'filament.custom.recent-files';

    protected function getWidgets(): array
    {
        return [
            StatsOverview::class,
        ];
    }
}
