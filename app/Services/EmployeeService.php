<?php

namespace App\Services;

use App\Models\Employee;

class EmployeeService
{
    /**
     * Retrieve all employees with their department info.
     */
    public function read()
    {
        return Employee::with('department')->get();
    }

    /**
     * Create a new employee record.
     */
    public function create(array $data)
    {
        $employee = Employee::create($data);
        return $employee->load('department');
    }

    /**
     * Update an existing employee.
     */
    public function update(Employee $employee, array $data)
    {
        $employee->update($data);
        return $employee;
    }

    /**
     * Remove an employee from the database.
     */
    public function delete(Employee $employee)
    {
        return $employee->delete();
    }
}
