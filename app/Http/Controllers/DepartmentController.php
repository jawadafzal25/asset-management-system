<?php

namespace App\Http\Controllers;

use App\Http\Requests\Department\DepartmentRequest;
use App\Http\Resources\Department\DepartmentResource;
use App\Services\DepartmentService;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    protected $departmentService;


     # Injecting the Service Layer into the Controller.

    public function __construct(DepartmentService $departmentService)
    {
        $this->departmentService = $departmentService;
    }


     # Handles both 'Read All' and 'Read Single' (via ID).

    public function read(Request $request, $id = null)
    {
        if ($id) {
            // The model was already fetched by the CheckDepartmentMiddleware
            $department = $request->attributes->get('department_data');
            
            // Debug: Check if department exists
            if (!$department) {
                return response()->error("Department data not found in middleware", 500);
            }
            
            return response()->success(new DepartmentResource($department));
        }

        $departments = $this->departmentService->read();
        return response()->success(DepartmentResource::collection($departments));
    }

   
    public function create(DepartmentRequest $request)
    {
        $department = $this->departmentService->create($request->validated());
        return response()->success(new DepartmentResource($department), "Department created successfully", 201);
    }


    public function update(DepartmentRequest $request, $id)
    {
        // Get model from middleware attribute
        $department = $request->attributes->get('department_data');

        if (!$department) {
            return response()->error("Department data not found in middleware", 500);
        }

        $updated = $this->departmentService->update($department, $request->validated());

        return response()->success(new DepartmentResource($updated), "Department updated successfully");
    }


    public function delete(Request $request, $id)
    {
        // Get model from middleware attribute
        $department = $request->attributes->get('department_data');

        if (!$department) {
            return response()->error("Department data not found in middleware", 500);
        }

        $this->departmentService->delete($department);

        return response()->success(null, "Department deleted successfully");
    }
}
