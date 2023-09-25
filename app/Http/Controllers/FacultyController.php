<?php

namespace App\Http\Controllers;

use App\Domain\Faculty\Actions\CreateFacultyAction;
use App\Domain\Faculty\DataTransferObjects\FacultyData;
use App\Domain\Faculty\Models\Faculty;
use App\Domain\Faculty\Requests\StoreFacultyRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FacultyController extends Controller
{
    public function store(StoreFacultyRequest $request)
    {
        $payload = $request->validated();

        $result = DB::transaction(
            fn () => app(CreateFacultyAction::class)
                ->execute(FacultyData::fromArray($payload))
        );

        if ($result instanceof Faculty) {
            return redirect()->route('register.index')->withFlashSuccess(__('Registered successfully, please check your email.'));
        }
    }
}
