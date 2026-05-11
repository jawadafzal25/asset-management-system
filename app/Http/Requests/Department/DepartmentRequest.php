<?php

namespace App\Http\Requests\Department;

use Illuminate\Foundation\Http\FormRequest;

class DepartmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Authorization is already handled by check.token middleware
        // This method only validates data structure, not permissions
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('id');
        
        // For create requests, don't include the ID in unique validation
        if ($this->isMethod('post')) {
            return [
                'department_name' => 'required|string|max:100|unique:departments,department_name'
            ];
        }
        
        // For update requests, exclude the current record from unique validation
        return [
            'department_name' => "required|string|max:100|unique:departments,department_name,$id,department_id"
        ];
    }
}
