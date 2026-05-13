<?php

namespace App\Http\Controllers;

use App\Http\Requests\Department\DepartmentRequest;
use App\Http\Resources\Department\DepartmentResource;
use App\Services\DepartmentService;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function __construct(protected DepartmentService $departmentService) {}

    public function read(Request $request)
    {
        $departments = $this->departmentService->read();
        return response()->success(DepartmentResource::collection($departments));
    }

    public function detail(Request $request)
    {
        $department = data_get($request->attributes->all(), 'department_data');
        return response()->success(new DepartmentResource($department));
    }

    public function create(DepartmentRequest $request)
    {
        $department = $this->departmentService->create($request->validated());
        return response()->success(new DepartmentResource($department), "Department created successfully", 201);
    }

    public function update(DepartmentRequest $request)
    {
        $department = data_get($request->attributes->all(), 'department_data');
        $updated = $this->departmentService->update($department, $request->validated());
        return response()->success(new DepartmentResource($updated), "Department updated successfully");
    }

    public function delete(Request $request)
    {
        $department = data_get($request->attributes->all(), 'department_data');
        $this->departmentService->delete($department);
        return response()->success(null, "Department deleted successfully");
    }
}
