<?php

namespace App\Http\Controllers;

use App\Domain\Faculty\Actions\CreateFacultyAction;
use App\Domain\Faculty\DataTransferObjects\FacultyData;
use App\Domain\Faculty\Enums\FacultyStatuses;
use App\Domain\Faculty\Models\Faculty;
use App\Domain\Faculty\Requests\StoreFacultyRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

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

    public function login(Request $request)
    {
        $validated = $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $faculty = Faculty::where('email', $validated['email'])
            ->where('status', FacultyStatuses::ACTIVE)
            ->first();

        if ($faculty === null || !Hash::check($validated['password'], $faculty->password)) {
            return redirect()->route('login.index')->with('error', 'Invalid credentials.');
        }

        if (auth()->guard('faculties')->attempt([
            'email' => $validated['email'],
            'password' => $validated['password'],
        ])) {
            session()->regenerate();

            // dd(auth()->guard('faculties')->user());

            return redirect(route('filament.pages./faculty/dashboard'));
        }
    }
}
