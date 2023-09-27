<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use App\Domain\Asset\Actions\DownloadSingleFileAction;
use App\Domain\Asset\Models\Asset;
use App\Domain\Folder\DataTransferObjects\DownloadData;
use App\Filament\Widgets\StatsOverview;
use App\Support\Concerns\AssetTrait;
use App\Support\Concerns\CustomFormatHelper;
use App\Support\Concerns\FolderTrait;
use Filament\Pages\Dashboard as BasePage;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class Dashboard extends BasePage
{
    use FolderTrait;
    use AssetTrait;
    use CustomFormatHelper;

    protected ?string $heading = '';

    protected static string $view = 'filament.custom.recent-files';

    public mixed $assetList;

    public function mount(): void
    {
        $this->fetchData();
    }

    protected function getWidgets(): array
    {
        return [
            StatsOverview::class,
        ];
    }

    private function fetchData(): void
    {
        $assetQueryData = $this->getAssets();
        $this->assetList = new Collection($assetQueryData->items());
    }

    /** @return LengthAwarePaginator<Asset> */
    public function getAssets(int $page = 1): LengthAwarePaginator
    {
        $result = Asset::with('folder')->orderBy('created_at', 'desc')
            ->paginate(5, page: $page);

        return $result;
    }

    public function mountActionAsset(string $action, int $assetId)
    {
        $asset = Asset::find($assetId);

        if ($asset) {
            return match ($action) {
                'open' => redirect(route('filament.resources.assets.edit', ['record' => $asset, 'ownerRecord' => $asset->folder ?? null])),
                'download' => app(DownloadSingleFileAction::class)->execute(
                    $asset,
                    DownloadData::fromArray(
                        [
                            'files' => [$asset->file],
                            'user_type' => 'admin',
                            'admin_id' => auth()->user()?->id,
                            'asset_type' => 'asset',
                            'asset_id' => $asset->id,
                        ]
                    )
                ),
                'delete' => $this->emitTo('filament.livewire.asset-modal', 'deleteAsset', $asset),
                'edit' => redirect(route('filament.resources.assets.edit', ['record' => $asset, 'ownerRecord' => $asset->folder])),
                'move-to' => $this->emitTo('filament.livewire.asset-modal', 'moveAssetToFolder', $asset),
                'show-history' => redirect(route('filament.pages./documents/history/{subjectType?}/{subjectId?}', ['subjectType' => 'assets', 'subjectId' => $asset->id])),
                default => null
            };
        }

        return null;
    }
}
