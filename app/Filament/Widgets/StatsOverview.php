<?php

namespace App\Filament\Widgets;

use App\Domain\Asset\Models\Asset;
use App\Domain\Faculty\Models\Faculty;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;

class StatsOverview extends BaseWidget
{
    protected function getCards(): array
    {
        $facultyCount = Faculty::whereNull('deleted_at')->count();
        $assetCount = Asset::whereNull('deleted_at')->count();
        $deletedAssetCount = Asset::onlyTrashed()->count();

        return [
            Card::make('Faculties', $facultyCount),
            Card::make('Documents', $assetCount),
            Card::make('Recycle Bin', $deletedAssetCount),
        ];
    }
}
