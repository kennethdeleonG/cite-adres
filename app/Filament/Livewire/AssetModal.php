<?php

declare(strict_types=1);

namespace App\Filament\Livewire;

use App\Domain\Asset\Actions\DeleteAssetAction;
use App\Domain\Asset\Models\Asset;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Livewire\Component;
use Illuminate\Contracts\View\View;
use Filament\Notifications\Notification;
use Illuminate\Support\Collection;

/**
 * @property \Filament\Forms\ComponentContainer $form
 */
class AssetModal extends Component implements HasForms
{
    use InteractsWithForms;

    public ?Asset $asset = null;
    public mixed $ownerRecordId = null;
    public ?string $assetName = null;
    public ?int $previousFolderId = null;
    public ?int $navigateFolderId = null;
    public ?string $navigateFolderName = null;

    /** @var array */
    protected $listeners = [
        'moveAssetToFolder' => 'moveAssetToFolderModal',
        'deleteAsset' => 'deleteAssetModal',
    ];

    public function mount(int $folderId = null): void
    {
        $this->ownerRecordId = $folderId;
        $this->form->fill();
    }

    public function render(): View
    {
        return view('filament.components.livewire.asset-modal');
    }

    //delete listener
    public function deleteAssetModal(array $data): void
    {
        $assetModel = Asset::find($data['id']);

        $this->dispatchBrowserEvent('open-modal', ['id' => 'delete-asset-modal-handle']);

        $this->asset = $assetModel instanceof Asset ? $assetModel : null;
    }

    // the delete handler
    public function deleteAction(): void
    {
        $recordToDelete = $this->asset;

        if (isset($this->asset)) {
            $result = app(DeleteAssetAction::class)->execute($this->asset);

            if ($result) {
                $this->emitUp('refreshPage', 'delete-asset', json_encode($recordToDelete));
                $this->dispatchBrowserEvent('close-modal', ['id' => 'delete-asset-modal-handle']);
                Notification::make()
                    ->title('Asset Deleted')
                    ->success()
                    ->send();
            }
        }
    }
}
