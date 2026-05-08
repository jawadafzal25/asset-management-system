<?php

namespace App\Services;

use App\Models\Department;

class DepartmentService
{
    /**
     * Get all departments from the database.
     */
    public function read()
    {
        return Department::all();
    }

    /**
     * Create a new department.
     */
    public function create(array $data)
    {
        return Department::create($data);
    }

    /**
     * Update an existing department instance.
     */
    public function update(Department $department, array $data)
    {
        $department->update($data);
        return $department;
    }

    /**
     * Delete a department instance.
     */
    public function delete(Department $department)
    {
        return $department->delete();
    }
}
