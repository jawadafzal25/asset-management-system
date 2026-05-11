<?php

namespace App\Http\Requests\Permission;

use Illuminate\Foundation\Http\FormRequest;

class PermissionIndexRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Only users with permission.view OR the main admin can list permissions
        return true; // gate handled by CheckPermission middleware on the route
    }

    public function rules(): array
    {
        return [
            'module'   => ['sometimes', 'string', 'max:100'],
            'per_page' => ['sometimes', 'integer', 'min:5', 'max:100'],
        ];
    }
}
