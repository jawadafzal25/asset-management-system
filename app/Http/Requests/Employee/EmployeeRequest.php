<?php

namespace App\Http\Requests\Employee;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Authorization is already handled by check.token middleware
        // This method only validates data structure, not permissions
        return true;
    }

    public function rules(): array
    {
        // Get the ID from the route to ignore the current employee's email during update
        $id = $this->route('id');

        // For create requests, don't include the ID in unique validation
        if ($this->isMethod('post')) {
            return [
                'name'          => 'required|string|max:255',
                'father_name'   => 'nullable|string|max:255',
                'contact_info'  => 'required|string|max:20',
                'email'         => 'required|email|unique:employees,email',
                'address'       => 'nullable|string',
                'designation'   => 'required|string|max:100',
                'joining_date'  => 'required|date',
                'salary'        => 'required|numeric|min:0',
                'status'        => 'required|in:active,inactive,on_leave',
                // Compulsory: Must exist in the department_id column of the departments table
                'department_id' => 'required|integer|exists:departments,department_id',
            ];
        }

        // For update requests, exclude the current record from unique validation
        return [
            'name'          => 'required|string|max:255',
            'father_name'   => 'nullable|string|max:255',
            'contact_info'  => 'required|string|max:20',
            'email'         => "required|email|unique:employees,email,$id,employee_id",
            'address'       => 'nullable|string',
            'designation'   => 'required|string|max:100',
            'joining_date'  => 'required|date',
            'salary'        => 'required|numeric|min:0',
            'status'        => 'required|in:active,inactive,on_leave',
            // Compulsory: Must exist in the department_id column of the departments table
            'department_id' => 'required|integer|exists:departments,department_id',
        ];
    }
}
