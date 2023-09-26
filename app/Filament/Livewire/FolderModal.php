<?php

namespace App\Filament\Livewire;

use App\Domain\Folder\Actions\DeleteFolderAction;
use App\Domain\Folder\Actions\UpdateFolderAction;
use App\Domain\Folder\DataTransferObjects\FolderData;
use App\Domain\Folder\Models\Folder;
use Livewire\Component;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Notifications\Notification;
use Illuminate\Support\Str;
use Filament\Forms;

class FolderModal extends Component implements HasForms
{
    use InteractsWithForms;

    public ?Folder $folder = null;
    public ?int $folderId = null;
    public ?string $folderName = null;
    public ?int $navigateFolderId = null;
    public ?string $navigateFolderName = null;
    public ?int $previousFolderId = null;
    public ?int $parentId = null;

    /** @var array */
    protected $listeners = [
        'editFolder' => 'editFolderModal',
        'deleteFolder' => 'deleteFolderModal',
        'moveFolder' => 'moveFolderModal',
        'closeMoveModal',
    ];

    public function mount(): void
    {
        $this->form->fill();
    }

    public function render()
    {
        return view('filament.components.livewire.folder-modal');
    }

    //edit folder modal
    protected function getFormSchema(): array
    {
        return [
            Forms\Components\TextInput::make('name')
                ->label('')
                ->validationAttribute('name')
                ->dehydrateStateUsing(function ($state) {
                    $existingRecords = Folder::where('name', 'LIKE', $state . '%')->where('folder_id', $this->parentId)
                        ->whereNot('id', $this->folderId)->count();
                    if ($existingRecords > 0) {
                        return $state . ' - (' . $existingRecords . ')';
                    }

                    return $state;
                }),
            Forms\Components\Toggle::make('is_private')->label('Private'),
        ];
    }

    //edit listener
    public function editFolderModal(array $data): void
    {
        $folderModel = Folder::with('assets')->find($data['id']);

        $this->dispatchBrowserEvent('open-modal', ['id' => 'edit-folder-modal-handle']);
        $this->form->fill([
            'name' => $data['name'],
            'is_private' => $data['is_private'],
        ]);

        $this->folder = $folderModel instanceof Folder ? $folderModel : null;
        $this->folderId = $folderModel instanceof Folder ? $folderModel->id : null;
        $this->folderName = $folderModel instanceof Folder ? $folderModel->name : null;
        $this->parentId = $folderModel instanceof Folder && $folderModel->parent ? $folderModel->parent->id : null;
    }

    //the edit handler
    public function editAction(): void
    {
        $folder_name = $this->form->getState()['name'];
        $folder_visibility = $this->form->getState()['is_private'];

        if (is_null($folder_name)) {
            return;
        }

        $updatedPath = '';
        if ($this->folder && !is_null($this->folder->parent)) {
            $folderPath = $this->folder->parent->path . '/' . Str::slug($folder_name);
            $updatedPath = $folderPath;
        } else {
            $updatedPath = '/' . Str::slug($folder_name);
        }

        $data['name'] = $folder_name;
        $data['slug'] = Str::slug($folder_name);
        $data['path'] = $updatedPath;
        $data['is_private'] = $folder_visibility;

        if (isset($this->folder)) {
            $result = app(UpdateFolderAction::class)
                ->execute($this->folder, FolderData::fromArray($data));

            if ($result instanceof Folder) {
                $this->emitUp('refreshPage', 'update', json_encode($result));
                $this->dispatchBrowserEvent('close-modal', ['id' => 'edit-folder-modal-handle']);
                Notification::make()
                    ->title('Folder Updated')
                    ->success()
                    ->send();
                if ($folder_name != $this->folderName) {
                    app(UpdateFolderAction::class)
                        ->updateDescendantPaths($this->folder, $updatedPath);
                }
            }
        }
    }

    //delete listener
    public function deleteFolderModal(array $data): void
    {
        $folderModel = Folder::find($data['id']);

        $this->dispatchBrowserEvent('open-modal', ['id' => 'delete-folder-modal-handle']);

        $this->folder = $folderModel instanceof Folder ? $folderModel : null;
    }

    // the delete handler
    public function deleteAction(): void
    {
        $recordToDelete = $this->folder;

        if (isset($this->folder)) {
            $result = app(DeleteFolderAction::class)->execute($this->folder);

            if ($result) {
                $this->emitUp('refreshPage', 'delete', json_encode($recordToDelete));
                $this->dispatchBrowserEvent('close-modal', ['id' => 'delete-folder-modal-handle']);
                Notification::make()
                    ->title('Folder Deleted')
                    ->success()
                    ->send();
            }
        }
    }
}
