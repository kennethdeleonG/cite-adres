<?php

namespace App\Filament\Resources\FacultyResource\Pages;

use App\Domain\Faculty\Actions\UpdateFacultyAction;
use App\Domain\Faculty\DataTransferObjects\FacultyData;
use App\Filament\Resources\FacultyResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class EditFaculty extends EditRecord
{
    protected static string $resource = FacultyResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        return DB::transaction(
            fn () => app(UpdateFacultyAction::class)
                ->execute($record, FacultyData::fromArray($data))
        );
    }
}
