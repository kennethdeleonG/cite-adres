<?php

namespace App\Filament\Livewire;

use App\Domain\Folder\Actions\DeleteFolderAction;
use App\Domain\Folder\Models\Folder;
use Livewire\Component;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Notifications\Notification;

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
