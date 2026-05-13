<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'            => ['nullable', 'string', 'max:255'],
            'profile_picture' => ['nullable'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.string' => 'Name must be a string.',
        ];
    }
}
