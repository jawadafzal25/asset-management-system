<?php

namespace App\Http\Requests\Department;

use Illuminate\Foundation\Http\FormRequest;

class DepartmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Only authenticated users (validated by check.token middleware) can authorize this request
        return auth()->check();
    }

    public function rules(): array
    {
        $id = $this->route('id');
        return [
            'department_name' => "required|string|max:100|unique:departments,department_name,$id,department_id"
        ];
    }
}
