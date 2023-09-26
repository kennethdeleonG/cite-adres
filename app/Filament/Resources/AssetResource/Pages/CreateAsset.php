<?php

namespace App\Filament\Resources\AssetResource\Pages;

use App\Domain\Asset\Actions\CreateAssetAction;
use App\Domain\Asset\DataTransferObjects\AssetData;
use App\Domain\Folder\Models\Folder;
use App\Filament\Resources\AssetResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CreateAsset extends CreateRecord
{
    protected ?string $heading = 'Create Document';

    protected static string $resource = AssetResource::class;

    public mixed $ownerRecord = null;

    public function mount(string $ownerRecord = ''): void
    {
        $this->ownerRecord = app(Folder::class)->resolveRouteBinding($ownerRecord);

        parent::mount();
    }

    protected static function canCreateAnother(): bool
    {
        return false;
    }

    protected function handleRecordCreation(array $data): Model
    {
        $data['folder_id'] = $this->ownerRecord ? $this->ownerRecord->id : null;
        $data['path'] = $this->ownerRecord ? $this->ownerRecord->path : '' . '/' . Str::slug($data['name']);
        $data['author_id'] = auth()->user()->id;


        return DB::transaction(
            fn () => app(CreateAssetAction::class)
                ->execute(AssetData::fromArray($data))
        );
    }

    protected function getRedirectUrl(): string
    {
        $resource = static::getResource();

        return $resource::getUrl('edit', [$this->record, $this->ownerRecord]);
    }
}
