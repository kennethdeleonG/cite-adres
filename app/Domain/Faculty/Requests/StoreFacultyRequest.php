<?php

namespace App\Domain\Faculty\Requests;

use App\Domain\Faculty\Models\Faculty;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class StoreFacultyRequest extends FormRequest
{
    public function rules()
    {
        return [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique(Faculty::class)
                    ->where('status', 'active'),
                'max:255',
            ],
            'password' => ['required', 'confirmed', Password::default()],
        ];
    }
}
