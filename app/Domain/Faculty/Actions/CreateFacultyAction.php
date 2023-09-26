<?php

declare(strict_types=1);

namespace App\Domain\Faculty\Actions;

use App\Domain\Faculty\DataTransferObjects\FacultyData;
use App\Domain\Faculty\Enums\RegisterStatus;
use App\Domain\Faculty\Models\Faculty;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\UploadedFile;

class CreateFacultyAction
{
    public function execute(FacultyData $facultyData): Faculty
    {
        $faculty =  Faculty::create([
            'first_name' => $facultyData->first_name,
            'last_name' => $facultyData->last_name,
            'email' => $facultyData->email,
            'password' => $facultyData->password,
        ]);

        if ($facultyData->image) {

            $faculty->addMediaFromDisk($facultyData->image->getRealPath(), 's3')->toMediaCollection('image');
        }

        event(new Registered($faculty));

        return $faculty;
    }
}
