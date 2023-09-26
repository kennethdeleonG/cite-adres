<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use App\Domain\Folder\Models\Folder as FolderModel;
use App\Domain\Folder\Actions\CreateFolderAction;
use App\Domain\Folder\DataTransferObjects\FolderData;
use App\Support\Concerns\CustomFormatHelper;
use App\Support\Concerns\CustomPagination;
use App\Support\Concerns\FileActions;
use App\Support\Concerns\FolderActions;
use Filament\Pages\Page;
use Filament\Pages\Actions;
use Filament\Forms;
use Illuminate\Support\Facades\DB;
use Filament\Notifications\Notification;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class Documents extends Page
{
    use FileActions;
    use CustomPagination;
    use CustomFormatHelper;

    public ?int $folder_id = null;

    protected ?string $heading = 'Documents';

    protected static string $view = 'filament.pages.documents';

    public function mount(string $folderId = null): void
    {
        $this->folder_id = intval($folderId);

        $this->fetchData();
    }

    private function fetchData(): void
    {
        $folderQueryData = $this->getFolders();
        $this->folderCount = $folderQueryData->lastPage();
        $this->folderList = new Collection($folderQueryData->items());

        // if (count($this->folderList) <= 16) {
        //     $assetQueryData = $this->getAssets();
        //     if (count($assetQueryData->items()) > 0) {
        //         $this->assetCount = $assetQueryData->lastPage();
        //     } else {
        //         $this->assetCount = 0;
        //     }
        //     $this->assetList = new Collection($assetQueryData->items());
        // }
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
                    ->label('New Document')
                    ->action(function () {
                        // temp comment uncomment if creation of assets will not be allowed on root directory
                        if ($this->folder_id <= 0) {
                            return Notification::make()
                                ->title('Please Create Document Inside Folders')
                                ->warning()
                                ->send();
                        }
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
}
