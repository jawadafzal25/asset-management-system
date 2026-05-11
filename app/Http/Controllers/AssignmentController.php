<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckoutAssetRequest;
use App\Http\Requests\CheckinAssetRequest;
use App\Http\Resources\AssignmentResource;
use App\Services\AssignmentService;

class AssignmentController extends Controller
{
    protected $assignmentService;

    public function __construct(AssignmentService $assignmentService)
    {
        $this->assignmentService = $assignmentService;
    }

    public function checkout(CheckoutAssetRequest $request)
    {
        // 1. Request se validated array
        $validatedData = $request->validated();
        
        $assignment = $this->assignmentService->checkout([
            'asset_id'    => data_get($validatedData, 'asset_id'),
            'employee_id' => data_get($validatedData, 'employee_id'),
            'quantity'    => data_get($validatedData, 'quantity', 1),
            'description' => data_get($validatedData, 'description'),
        ]);
        
        return response()->success(
            new AssignmentResource($assignment), 
            'Asset checked out successfully.', 
            201
        );
    }

    public function checkin(CheckinAssetRequest $request)
    {
        $validatedData = $request->validated();

        $assignment = $this->assignmentService->checkin([
            'asset_id' => data_get($validatedData, 'asset_id'),
        ]);
        
        return response()->success(
            new AssignmentResource($assignment), 
            'Asset checked in successfully.', 
            200
        );
    }

    public function history($assetId = null) 
    {
        $history = $this->assignmentService->getHistory($assetId);
        
        return response()->success(
            AssignmentResource::collection($history), 
            'Asset history fetched successfully.', 
            200
        );
    }
}