<?php

namespace App\Filament\Resources\AssetResource\Pages;

use App\Domain\Folder\Models\Folder;
use App\Filament\Resources\AssetResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateAsset extends CreateRecord
{
    protected ?string $heading = 'Create Document';

    protected static string $resource = AssetResource::class;

    public mixed $ownerRecord = null;

    protected static function canCreateAnother(): bool
    {
        return false;
    }
}
