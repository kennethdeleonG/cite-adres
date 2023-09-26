<?php

namespace App\Filament\Resources\FacultyResource\Pages;

use App\Domain\Faculty\Actions\CreateFacultyAction;
use App\Domain\Faculty\DataTransferObjects\FacultyData;
use App\Filament\Resources\FacultyResource;
use Filament\Notifications\Notification;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class CreateFaculty extends CreateRecord
{
    protected static string $resource = FacultyResource::class;

    protected static function canCreateAnother(): bool
    {
        return false;
    }

    protected function handleRecordCreation(array $data): Model
    {
        return DB::transaction(
            fn () => app(CreateFacultyAction::class)
                ->execute(FacultyData::fromArray($data))
        );
    }
}
