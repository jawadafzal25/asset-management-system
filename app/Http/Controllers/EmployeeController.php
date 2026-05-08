<?php

namespace App\Http\Controllers;

use App\Http\Requests\Employee\EmployeeRequest;
use App\Http\Resources\Employee\EmployeeResource;
use App\Services\EmployeeService;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    protected $employeeService;

    public function __construct(EmployeeService $employeeService)
    {
        $this->employeeService = $employeeService;
    }

    public function read(Request $request, $id = null)
    {
        if ($id) {
            // Get from Middleware attribute
            $employee = $request->attributes->get('employee_data');
            // Load department relationship for the single view
            return response()->success(new EmployeeResource($employee->load('department')));
        }

        $employees = $this->employeeService->read();
        return response()->success(EmployeeResource::collection($employees));
    }

    public function create(EmployeeRequest $request)
    {
        $employee = $this->employeeService->create($request->validated());
        return response()->success(new EmployeeResource($employee), "Employee created successfully", 201);
    }

    public function update(EmployeeRequest $request, $id)
    {
        $employee = $request->attributes->get('employee_data');
        $updated = $this->employeeService->update($employee, $request->validated());

        return response()->success(new EmployeeResource($updated), "Employee updated successfully");
    }

    public function delete(Request $request, $id)
    {
        $employee = $request->attributes->get('employee_data');
        $this->employeeService->delete($employee);

        return response()->success(null, "Employee deleted successfully");
    }
}
