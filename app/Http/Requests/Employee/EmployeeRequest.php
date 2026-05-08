<?php

namespace App\Http\Requests\Employee;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Set to true since we aren't using Auth yet
    }

    public function rules(): array
    {
        // Get the ID from the route to ignore the current employee's email during update
        $id = $this->route('id');

        return [
            'name'          => 'required|string|max:255',
            'father_name'   => 'nullable|string|max:255',
            'contact_info'  => 'required|string|max:20',
            #Unique check ignores the current record's ID during update
            'email'         => 'required|email|unique:employees,email,' . $id . ',employee_id',
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
