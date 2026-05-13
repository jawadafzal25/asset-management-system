<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use App\Services\MaintenanceService;
use App\Http\Resources\MaintenanceResource;
use App\Http\Requests\Maintenance\CreateMaintenanceRequest;
use App\Http\Requests\Maintenance\UpdateMaintenanceRequest;

class MaintenanceController extends Controller
{
    public function __construct(
        protected MaintenanceService $service
    ) {}

    public function create(
        CreateMaintenanceRequest $request
    ) {
        $data = $this->service->create(
            $request->validated()
        );

        return response()->success(
            new MaintenanceResource($data),
            'Maintenance created successfully',
            201
        );
    }

    public function read(Request $request)
    {
        $data = $this->service->readAll(
            data_get($request, 'search')
        );

        return response()->success(
            MaintenanceResource::collection($data),
            'Maintenance records fetched successfully'
        );
    }

    public function show($id)
    {
        $data = $this->service->read($id);

        return response()->success(
            new MaintenanceResource($data),
            'Maintenance detail fetched successfully'
        );
    }

    public function update(
        UpdateMaintenanceRequest $request,
        $id
    ) {
        $data = $this->service->update(
            $id,
            $request->validated()
        );

        return response()->success(
            new MaintenanceResource($data),
            'Maintenance updated successfully'
        );
    }

    public function delete($id)
    {
        $this->service->delete($id);

        return response()->success(
            null,
            'Maintenance deleted successfully'
        );
    }
}