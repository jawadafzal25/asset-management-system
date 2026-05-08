<?php

namespace App\Http\Requests\Department;

use Illuminate\Foundation\Http\FormRequest;

class DepartmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Set to true to allow the request
    }

    public function rules(): array
    {
        $id = $this->route('id');
        return [
            'department_name' => "required|string|max:100|unique:departments,department_name,$id,department_id"
        ];
    }
}
