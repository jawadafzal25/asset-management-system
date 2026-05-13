<?php

namespace App\Http\Requests\Role;

use Illuminate\Foundation\Http\FormRequest;

class RoleUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'sometimes|required|string|max:255|unique:roles,name,' . $this->route('id'),
            'slug' => 'sometimes|required|string|max:255|unique:roles,slug,' . $this->route('id'),
            'description' => 'nullable|string|max:1000',
            'is_active' => 'nullable|boolean',
            'permissions' => 'nullable|array',
            'permissions.*' => 'integer|exists:permissions,permission_id',
            'permission' => 'nullable|array',
            'permission.*' => 'integer|exists:permissions,permission_id',
        ];
    }

    /**
     * Get custom error messages for validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Role name is required.',
            'name.unique' => 'Role name already exists.',
            'permissions.array' => 'Permissions must be an array.',
            'permissions.*.exists' => 'One or more selected permissions are invalid.',
            'permission.array' => 'Permission must be an array.',
            'permission.*.exists' => 'One or more selected permissions are invalid.',
        ];
    }
}
