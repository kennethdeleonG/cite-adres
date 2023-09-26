<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use App\Domain\Asset\Models\Asset;
use App\Domain\Folder\Models\Folder as FolderModel;
use App\Domain\Folder\Actions\CreateFolderAction;
use App\Domain\Folder\DataTransferObjects\FolderData;
use App\Support\Concerns\AssetTrait;
use App\Support\Concerns\CustomFormatHelper;
use App\Support\Concerns\CustomPagination;
use App\Support\Concerns\FolderTrait;
use Filament\Pages\Page;
use Filament\Pages\Actions;
use Filament\Forms;
use Illuminate\Support\Facades\DB;
use Filament\Notifications\Notification;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Filament\Navigation\NavigationItem;

class Documents extends Page
{
    use FolderTrait;
    use AssetTrait;
    use CustomPagination;
    use CustomFormatHelper;

    public ?int $folder_id = null;

    protected ?string $heading = 'Documents';

    protected static string $view = 'filament.pages.documents';

    protected static ?string $slug = '/documents/{folderId?}';

    /** @var array */
    protected $listeners = [
        'refreshPage' => 'refreshingFolder',
        'folderIdUpdated' => 'getFolderWithId',
    ];

    public function mount(string $folderId = null): void
    {
        $this->folder_id = intval($folderId);

        $this->fetchData();
    }

    public static function getNavigationItems(): array
    {
        return [
            NavigationItem::make(static::getNavigationLabel())
                ->group(static::getNavigationGroup())
                ->icon(static::getNavigationIcon())
                ->isActiveWhen(fn (): bool => request()->routeIs('filament.resources.assets.*')
                    || request()->routeIs("filament.pages./documents/*"))
                ->sort(static::getNavigationSort())
                ->badge(static::getNavigationBadge(), color: static::getNavigationBadgeColor())
                ->url(static::getNavigationUrl()),
        ];
    }

    private function fetchData(): void
    {
        $folderQueryData = $this->getFolders();
        $this->folderCount = $folderQueryData->lastPage();
        $this->folderList = new Collection($folderQueryData->items());

        if (count($this->folderList) <= 16) {
            $assetQueryData = $this->getAssets();
            if (count($assetQueryData->items()) > 0) {
                $this->assetCount = $assetQueryData->lastPage();
            } else {
                $this->assetCount = 0;
            }
            $this->assetList = new Collection($assetQueryData->items());
        }
    }

    /** @return LengthAwarePaginator<Asset> */
    public function getAssets(int $page = 1): LengthAwarePaginator
    {

        $result = Asset::with('folder')->where(function ($query) {
            if ($this->folder_id) {
                $query->where('folder_id', $this->folder_id);
            } else {
                $query->whereNull('folder_id');
            }
        })->orderBy('name')
            ->paginate(32, page: $page);

        return $result;
    }

    /** @return LengthAwarePaginator<FolderModel> */
    public function getFolders(int $page = 1): LengthAwarePaginator
    {
        $result = FolderModel::with(['descendants'])->where(function ($query) {
            if ($this->folder_id) {
                $query->where('folder_id', $this->folder_id);
            } else {
                $query->whereNull('folder_id');
            }
        })->orderBy('name')
            ->paginate(32, page: $page);

        return $result;
    }

    //right actions
    protected function getActions(): array
    {
        return [
            Actions\ActionGroup::make([
                Actions\Action::make('new-folder')
                    ->label('New Folder')
                    ->modalHeading('New Folder')
                    ->modalWidth('md')
                    ->form([
                        Forms\Components\TextInput::make('name')
                            ->label('')
                            ->dehydrateStateUsing(function ($state) {
                                if ($this->folder_id == 0) {
                                    $existingRecords = DB::table('folders')->where('name', 'LIKE', $state . '%')->whereNull('folder_id')->count();
                                    if ($existingRecords > 0) {
                                        return $state . ' - (' . $existingRecords . ')';
                                    }

                                    return $state;
                                } else {
                                    $existingRecords = DB::table('folders')->where('name', 'LIKE', $state . '%')->where('folder_id', $this->folder_id)->count();
                                    if ($existingRecords > 0) {
                                        return $state . ' - (' . $existingRecords . ')';
                                    }

                                    return $state;
                                }
                            }),
                        Forms\Components\Toggle::make('is_private')->label('Private')->default(false),
                    ])
                    ->action('createFolder'),
                Actions\Action::make('new-asset')
                    ->label('New Asset')
                    ->action(function () {
                        $folder = FolderModel::find($this->folder_id);

                        return redirect()->route('filament.resources.assets.create', $folder);
                    }),
            ])
                ->view('filament.components.custom-action-group.index')
                ->label('Create New'),
        ];
    }

    public function createFolder(array $data): void
    {
        if (is_null($data['name'])) {
            return;
        }

        $folder = FolderModel::find($this->folder_id);

        $path = '';
        if (!is_null($folder) && !empty($folder->path)) {
            $path = $folder->path;
        }

        $data['author_id'] = auth()->user()->id;
        $data['slug'] = Str::slug($data['name']);
        $data['path'] = $path . '/' . Str::slug($data['name']);
        $data['folder_id'] = $this->folder_id;

        $result = app(CreateFolderAction::class)
            ->execute(FolderData::fromArray($data));

        if ($result instanceof FolderModel) {
            $this->refreshingFolder('create', $result);
            Notification::make()
                ->title('Folder Created')
                ->success()
                ->send();
        }
    }

    public function refreshingFolder(string $action, mixed $record): void
    {
        switch ($action) {
            case 'create': {
                    $this->fetchData();

                    break;
                }
            default: {
                    break;
                }
        }
    }

    public function getFolderSize(int $folder_id): string
    {
        $folder = $this->folderList->where('id', $folder_id)->first();

        $folderIds = $folder->descendants->pluck('id')->toArray();

        return $this->convertedAssetSize(
            (int) Asset::whereIn('folder_id', $folderIds)
                ->orWhere('folder_id', $folder_id)
                ->sum('size')
        );
    }

    public function mountActionFolder(string $action, int $folderId): void
    {
        $folder = $this->folderList->where('id', $folderId)->first();

        if ($folder) {
            switch ($action) {
                case 'open': {
                        $this->getFolderWithId($folder->id);

                        break;
                    }
                default: {
                        break;
                    }
            }
        }
    }

    public function mountActionAsset(string $action, int $assetId)
    {
        $asset = Asset::find($assetId);

        if ($asset) {
            return match ($action) {
                'open' => redirect(route('filament.resources.assets.edit', ['record' => $asset, 'ownerRecord' => $asset->folder ?? null])),
                default => null
            };
        }

        return null;
    }

    /**
     * Get an instance of the redirector.
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function getFolderWithId(int $folderId)
    {
        return redirect()->to(self::getUrl() . '/' . $folderId);
    }
}
