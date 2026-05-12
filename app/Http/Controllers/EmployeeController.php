<?php

namespace App\Http\Controllers;

use App\Http\Requests\Employee\EmployeeRequest;
use App\Http\Resources\Employee\EmployeeResource;
use App\Services\EmployeeService;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function __construct(protected EmployeeService $employeeService) {}

    public function read(Request $request)
    {
        $employees = $this->employeeService->read();
        return response()->success(EmployeeResource::collection($employees));
    }

    public function detail(Request $request)
    {
        $employee = data_get($request->attributes->all(), 'employee_data');
        return response()->success(new EmployeeResource($employee));
    }

    public function create(EmployeeRequest $request)
    {
        $employee = $this->employeeService->create($request->validated());
        return response()->success(new EmployeeResource($employee), "Employee created successfully", 201);
    }

    public function update(EmployeeRequest $request)
    {
        $employee = data_get($request->attributes->all(), 'employee_data');
        $updated = $this->employeeService->update($employee, $request->validated());
        return response()->success(new EmployeeResource($updated), "Employee updated successfully");
    }

    public function delete(Request $request)
    {
        $employee = data_get($request->attributes->all(), 'employee_data');
        $this->employeeService->delete($employee);
        return response()->success(null, "Employee deleted successfully");
    }
}
