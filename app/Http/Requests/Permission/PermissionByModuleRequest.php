<?php

namespace App\Http\Requests\Permission;

use Illuminate\Foundation\Http\FormRequest;

class PermissionByModuleRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Access control is handled by middleware on the route
        return true;
    }

    public function rules(): array
    {
        return [
            'module' => ['required', 'string', 'max:100'],
        ];
    }

    public function messages(): array
    {
        return [
            'module.required' => 'The module query parameter is required. e.g. ?module=Employee Management',
        ];
    }
}
