<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class ResetPasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'verification_code' => ['required', 'string', 'digits:6'],
            'password'         => ['required', 'string', 'min:8'],
            'confirm_password' => ['required', 'same:password'],
        ];
    }

    public function messages(): array
    {
        return [
            'verification_code.required'     => 'Reset code is required.',
            'verification_code.digits'       => 'Reset code must be 6 digits.',
            'password.required'         => 'Password is required.',
            'password.min'              => 'Password must be at least 8 characters.',
            'confirm_password.required' => 'Please confirm your password.',
            'confirm_password.same'     => 'Passwords do not match.',
        ];
    }
}
